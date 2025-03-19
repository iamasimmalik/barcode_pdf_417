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

            // Validate all input fields with simplified validation
            $validated = $request->validate([
                'NUMBER' => 'required|string',
                'LASTNAME' => 'required|string',
                'FIRSTNAME' => 'required|string',
                'MIDDLENAME' => 'nullable|string',
                'ADDRESS' => 'required|string',
                'CITY' => 'required|string',
                'ZIP' => 'required|string',
                'STATE' => 'required|string',
                'CLASS' => 'required|string',
                'SEX' => 'required|in:M,F',
                'DONOR' => 'required|in:YES,NO',
                'DOB' => 'required|string',
                'DOI' => 'required|string',
                'DOE' => 'required|string',
                'HEIGHT' => 'required|numeric|min:30|max:100',
                'WEIGHT' => 'required|numeric|min:50|max:500',
                'EYE' => 'required|in:BLK,BLU,BRO,GRY,GRN,HAZ,MAR',
                'HAIR' => 'required|in:BLK,BLN,BRO,GRY,RED,WHI,BAL',
                'RESTRICTIONS' => 'required|string',
                'ENDORSEMENT' => 'required|string',
                // New AAMVA fields
                'AUDITINFO' => 'nullable|string',
                'INVENTORYNUM' => 'nullable|string',
                'REVISIONDATE' => 'nullable|string',
                'DISCRIMINATOR' => 'nullable|string',
                'SSN' => 'nullable|string'
            ]);

            Log::info('Validation passed successfully');

            // Format SSN properly for barcode data (XXX-XX-XXXX)
            $formattedSSN = '';
            if ($request->filled('SSN')) {
                $ssn = str_replace(['-', ' '], '', $request->SSN); // Remove dashes and spaces
                if (is_numeric($ssn) && strlen($ssn) === 9) {
                    $formattedSSN = substr($ssn, 0, 3) . '-' . substr($ssn, 3, 2) . '-' . substr($ssn, 5, 4);
                } else {
                    Log::warning('SSN format is invalid: ' . $request->SSN);
                }
            }

            // Format the data in a structured way for PDF417 - AAMVA standard format
            $barcodeData = implode("\n", [
                '@',  // Start sentinel
                'ANSI ' .
                '636000' .
                '07' .
                '00' .
                '02' .
                'DL' .
                '0041' .
                '0278' .
                'ZV' .
                '0319' .
                '0008' .
                'DL',  // Header format exactly matching the sample
                'DAQ' . str_replace('-', '', $request->NUMBER),  // License number
                'DAA' . strtoupper($request->LASTNAME) . ',' . strtoupper($request->FIRSTNAME) . ',' . ($request->MIDDLENAME ? strtoupper($request->MIDDLENAME) : '') . ',',  // Full name format
                'DAG' . strtoupper($request->ADDRESS),  // Address
                'DAI' . strtoupper($request->CITY),  // City
                'DAJ' . strtoupper($request->STATE),  // State/province
                'DCJ' . ($request->filled('AUDITINFO') ? strtoupper($request->AUDITINFO) : ''),  // Audit information
                'DCK' . ($request->filled('INVENTORYNUM') ? strtoupper($request->INVENTORYNUM) : ''),  // Inventory control number
                'DDB' . ($request->filled('REVISIONDATE') ? $request->REVISIONDATE : ''),  // Revision date
                'DCF' . ($request->filled('DISCRIMINATOR') ? strtoupper($request->DISCRIMINATOR) : ''),  // Discriminator
                'DBM' . ($request->filled('SSN') ? str_replace('-', '', $formattedSSN) : ''),  // Social Security Number
                'DAK' . strtoupper($request->ZIP),  // ZIP
                'DAR' . strtoupper($request->CLASS),  // Class
                'DAU' . str_pad($request->HEIGHT, 3, '0', STR_PAD_LEFT) . ' cm',  // Height with cm units
                'DAX' . str_pad($request->WEIGHT, 3, '0', STR_PAD_LEFT),  // Weight using DAX code as in sample
                'DAY' . $request->EYE,  // Eye color
                'DAZ' . $request->HAIR,  // Hair color
                'DBA' . $request->DOE,  // Date of expiry
                'DBB' . $request->DOB,  // Date of birth
                'DBC' . $request->SEX,  // Sex
                'DBD' . $request->DOI,  // Issue date with correct DBD code
                'DDD' . $request->DONOR,  // Donor
                'ZNZ'  // End sentinel
            ]);

            // Filter out empty elements that might have been added for conditional fields
            $barcodeData = implode("\n", array_filter(explode("\n", $barcodeData), function($line) {
                // Keep lines that either don't have conditional fields or have data for conditional fields
                return !(
                    (strpos($line, 'DCJ') === 0 && substr($line, 3) === '') ||
                    (strpos($line, 'DCK') === 0 && substr($line, 3) === '') ||
                    (strpos($line, 'DDB') === 0 && substr($line, 3) === '') ||
                    (strpos($line, 'DCF') === 0 && substr($line, 3) === '') ||
                    (strpos($line, 'DBM') === 0 && substr($line, 3) === '')
                );
            }));

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
                    <div class="barcode-container">
                        <img src="' . $base64 . '" alt="PDF417 Barcode" class="barcode-image" id="barcodeImage">
                        <div class="barcode-caption">
                            Scan this barcode
                        </div>
                        <button type="button" class="btn download-btn" onclick="downloadBarcode()">
                            <i class="fas fa-download"></i> Download Barcode
                        </button>
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
                        <div class="barcode-container">
                            <img src="' . $base64 . '" alt="PDF417 Barcode (Fallback)" class="barcode-image" id="barcodeImage">
                            <div class="barcode-caption">
                                Scan this barcode (Fallback Mode)
                            </div>
                            <button type="button" class="btn download-btn" onclick="downloadBarcode()">
                                <i class="fas fa-download"></i> Download Barcode
                            </button>
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
                            <div class="barcode-container">
                                <img src="' . $base64 . '" alt="PDF417 Barcode (Last Resort)" class="barcode-image" id="barcodeImage">
                                <div class="barcode-caption">
                                    Scan this barcode (Minimal Mode)
                                </div>
                                <button type="button" class="btn download-btn" onclick="downloadBarcode()">
                                    <i class="fas fa-download"></i> Download Barcode
                                </button>
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
                'message' => 'Failed to generate barcode: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString()),
                    'request_data' => $request->all()
                ]
            ], 500);
        }
    }

    /**
     * Debug method to help diagnose barcode generation issues
     */
    public function debug(Request $request)
    {
        try {
            // Get library version information
            $barcode = new Barcode();
            $reflector = new \ReflectionClass($barcode);
            $libraryPath = $reflector->getFileName();

            // Get available barcode types
            $types = [];
            if (method_exists($barcode, 'getTypes')) {
                $types = $barcode->getTypes();
            }

            // Test basic PDF417 generation
            $testData = "TEST DATA FOR PDF417 BARCODE";
            $testBarcode = null;
            $testError = null;

            try {
                $testObj = $barcode->getBarcodeObj(
                    'PDF417',
                    $testData,
                    300,
                    100,
                    'black',
                    [0, 0, 0, 0]
                );
                $testBarcode = 'data:image/png;base64,' . base64_encode($testObj->getPngData());
            } catch (\Exception $e) {
                $testError = $e->getMessage();
            }

            return response()->json([
                'success' => true,
                'library_info' => [
                    'path' => $libraryPath,
                    'class' => get_class($barcode),
                    'available_types' => $types,
                ],
                'php_info' => [
                    'version' => PHP_VERSION,
                    'extensions' => get_loaded_extensions(),
                ],
                'test_barcode' => [
                    'data' => $testData,
                    'image' => $testBarcode,
                    'error' => $testError,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
