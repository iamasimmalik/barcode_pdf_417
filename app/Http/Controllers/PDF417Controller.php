<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Com\Tecnick\Barcode\Barcode;

class PDF417Controller extends Controller
{
    public function index()
    {
        return view('pdf417.index');
    }

    public function generate(Request $request)
    {
        try {
            Log::info('Starting barcode generation process');
            Log::debug('Received request data:', $request->all());

            // Validate all input fields with strict validation
            $validated = $request->validate([
                'NUMBER' => 'required|regex:/^\d{4}-\d{2}-\d{4}$/',
                'LASTNAME' => 'required|regex:/^[a-zA-Z\s]+$/',
                'FIRSTNAME' => 'required|regex:/^[a-zA-Z\s]+$/',
                'MIDDLENAME' => 'nullable|regex:/^[a-zA-Z\s]*$/',
                'ADDRESS' => 'required|string',
                'CITY' => 'required|string',
                'ZIP' => 'required|regex:/^\d{5}$/',
                'STATE' => 'required|regex:/^[A-Z]{2}$/',  // Two-letter state code
                'CLASS' => 'required|regex:/^[A-Z0-9]+$/',
                'SEX' => 'required|in:M,F',
                'DONOR' => 'required|in:YES,NO',
                'DOB' => 'required|regex:/^\d{8}$/',
                'DOI' => 'required|regex:/^\d{8}$/',
                'DOE' => 'required|regex:/^\d{8}$/',
                'HEIGHT' => 'required|numeric|min:30|max:100',
                'WEIGHT' => 'required|numeric|min:50|max:500',
                'EYE' => 'required|in:BLK,BLU,BRO,GRY,GRN,HAZ,MAR',
                'HAIR' => 'required|in:BLK,BLN,BRO,GRY,RED,WHI,BAL',
                'RESTRICTIONS' => 'required|string',
                'ENDORSEMENT' => 'required|string'
            ]);

            Log::info('Validation passed successfully');

            // Format the data in a structured way for PDF417 - AAMVA standard format
            $barcodeData = implode("\n", [
                '@',  // Start sentinel
                'ANSI 636014040002DL00410288ZN04330047DLDCANONE',  // Header
                'DL' . str_replace('-', '', $request->NUMBER),
                'DCSN' . strtoupper($request->LASTNAME),
                'DACN' . strtoupper($request->FIRSTNAME),
                'DAD' . ($request->MIDDLENAME ? strtoupper($request->MIDDLENAME) : ''),
                'DBB' . $request->DOB,
                'DBA' . $request->DOE,
                'DBC' . $request->SEX,
                'DAU' . str_pad($request->HEIGHT, 3, '0', STR_PAD_LEFT),
                'DAW' . str_pad($request->WEIGHT, 3, '0', STR_PAD_LEFT),
                'DAY' . $request->EYE,
                'DAZ' . $request->HAIR,
                'DAG' . strtoupper($request->ADDRESS),
                'DAI' . strtoupper($request->CITY),
                'DAJ' . strtoupper($request->ZIP),
                'DAK' . strtoupper($request->STATE),  // Using user-provided state instead of hardcoded 'TX'
                'DBD' . $request->RESTRICTIONS,
                'DBE' . $request->ENDORSEMENT,
                'DDD' . $request->DONOR,
                'ZNZ'  // End sentinel
            ]);

            Log::debug('Formatted barcode data:', ['data' => $barcodeData]);

            try {
                Log::info('Initializing barcode object');
                // Ensure the Barcode class is available and properly loaded
                if (!class_exists('Com\Tecnick\Barcode\Barcode')) {
                    throw new Exception('Barcode library is not available. Please check your composer dependencies.');
                }

                $barcode = new Barcode();
                Log::info('Barcode object created successfully');

                // Configure the barcode for PDF417
                Log::info('Generating barcode with options');
                // The padding parameter must be an array with exactly 4 elements
                $padding = [0, 0, 0, 0]; // top, right, bottom, left - using zeros to avoid padding issues

                Log::debug('Barcode padding:', ['padding' => $padding]);

                $bObj = $barcode->getBarcodeObj(
                    'PDF417',       // Barcode type
                    $barcodeData,   // Data to encode
                    300,            // Width - increased for better scanning
                    120,            // Height - increased for proper aspect ratio
                    'black',        // Foreground color
                    $padding        // Padding as a separate parameter, not in options
                );

                Log::info('Barcode object generated successfully');

                // Get PNG data directly with error checking
                Log::info('Converting barcode to PNG');
                if (!method_exists($bObj, 'getPngData')) {
                    throw new Exception('getPngData method not found on barcode object');
                }

                $pngData = $bObj->getPngData();

                if (empty($pngData)) {
                    throw new Exception('Generated PNG data is empty');
                }

                $base64 = 'data:image/png;base64,' . base64_encode($pngData);
                Log::info('PNG conversion successful');

                // Create an img tag with proper styling for clarity
                $wrappedBarcode = '
                    <div style="padding:20px; background-color:#fff; display:inline-block; border:2px solid #ddd; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.15); margin:10px 0;">
                        <img src="' . $base64 . '" alt="PDF417 Barcode" style="max-width:100%; height:auto; display:block;">
                        <div style="margin-top:10px; text-align:center; font-family:Arial, sans-serif; font-size:14px; color:#333; font-weight:bold;">
                            Scan this barcode
                        </div>
                    </div>';

                Log::info('Barcode generation completed successfully');
                return response()->json([
                    'success' => true,
                    'barcode' => $wrappedBarcode,
                    'message' => 'Barcode generated successfully'
                ]);

            } catch (Exception $innerException) {
                Log::error('PDF417 Generation Error Details:', [
                    'message' => $innerException->getMessage(),
                    'file' => $innerException->getFile(),
                    'line' => $innerException->getLine(),
                    'trace' => $innerException->getTraceAsString(),
                    'previous' => $innerException->getPrevious() ? $innerException->getPrevious()->getMessage() : null
                ]);

                // Try a fallback configuration if the first attempt failed
                try {
                    Log::info('Attempting fallback barcode generation with simplified options');

                    $barcode = new Barcode();
                    // Use zeros for padding in fallback to avoid padding issues
                    $fallbackPadding = [0, 0, 0, 0];

                    $bObj = $barcode->getBarcodeObj(
                        'PDF417',
                        $barcodeData,
                        300,            // Width - increased for better scanning
                        120,            // Height - increased for proper aspect ratio
                        'black',
                        $fallbackPadding
                    );

                    $pngData = $bObj->getPngData();
                    $base64 = 'data:image/png;base64,' . base64_encode($pngData);

                    $wrappedBarcode = '
                        <div style="padding:20px; background-color:#fff; display:inline-block; border:2px solid #ddd; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.15); margin:10px 0;">
                            <img src="' . $base64 . '" alt="PDF417 Barcode (Fallback)" style="max-width:100%; height:auto; display:block;">
                            <div style="margin-top:10px; text-align:center; font-family:Arial, sans-serif; font-size:14px; color:#333; font-weight:bold;">
                                Scan this barcode (Fallback Mode)
                            </div>
                        </div>';

                    Log::info('Fallback barcode generation successful');

                    return response()->json([
                        'success' => true,
                        'barcode' => $wrappedBarcode,
                        'message' => 'Barcode generated using fallback options'
                    ]);

                } catch (Exception $fallbackException) {
                    Log::error('Fallback barcode generation also failed:', [
                        'message' => $fallbackException->getMessage()
                    ]);

                    // Try one last approach with absolutely no options
                    try {
                        Log::info('Attempting last resort barcode generation with no options');

                        $barcode = new Barcode();
                        // No options at all, with zero padding
                        $lastResortPadding = [0, 0, 0, 0];
                        $bObj = $barcode->getBarcodeObj(
                            'PDF417',
                            $barcodeData,
                            300,            // Width - increased for better scanning
                            120,            // Height - increased for proper aspect ratio
                            'black',
                            $lastResortPadding
                        );

                        $pngData = $bObj->getPngData();
                        $base64 = 'data:image/png;base64,' . base64_encode($pngData);

                        $wrappedBarcode = '
                            <div style="padding:20px; background-color:#fff; display:inline-block; border:2px solid #ddd; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.15); margin:10px 0;">
                                <img src="' . $base64 . '" alt="PDF417 Barcode (Last Resort)" style="max-width:100%; height:auto; display:block;">
                                <div style="margin-top:10px; text-align:center; font-family:Arial, sans-serif; font-size:14px; color:#333; font-weight:bold;">
                                    Scan this barcode (Minimal Mode)
                                </div>
                            </div>';

                        Log::info('Last resort barcode generation successful');

            return response()->json([
                'success' => true,
                            'barcode' => $wrappedBarcode,
                            'message' => 'Barcode generated with minimal settings'
                        ]);
                    } catch (Exception $lastException) {
                        Log::error('Last resort barcode generation also failed:', [
                            'message' => $lastException->getMessage()
                        ]);
                        throw new Exception('Failed to generate PDF417 barcode with all attempts: ' . $innerException->getMessage());
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('PDF417 Controller Error Details:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'previous' => $e->getPrevious() ? $e->getPrevious()->getMessage() : null,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate barcode: ' . $e->getMessage()
            ], 500);
        }
    }
}
