<?php
	function fGetRGB($iH, $iS, $iV) {

		if($iH < 0)   $iH = 0;   // Hue:
		if($iH > 360) $iH = 360; //   0-360
		if($iS < 0)   $iS = 0;   // Saturation:
		if($iS > 100) $iS = 100; //   0-100
		if($iV < 0)   $iV = 0;   // Lightness:
		if($iV > 100) $iV = 100; //   0-100

		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness:  0.0-1.0
		$dC = $dV*$dS;   // Chroma:     0.0-1.0
		$dH = $iH/60.0;  // H-Prime:    0.0-6.0
		$dT = $dH;       // Temp variable

		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1));     // as used in the Wikipedia link

		switch($dH) {
			case($dH >= 0.0 && $dH < 1.0):
				$dR = $dC; $dG = $dX; $dB = 0.0; break;
			case($dH >= 1.0 && $dH < 2.0):
				$dR = $dX; $dG = $dC; $dB = 0.0; break;
			case($dH >= 2.0 && $dH < 3.0):
				$dR = 0.0; $dG = $dC; $dB = $dX; break;
			case($dH >= 3.0 && $dH < 4.0):
				$dR = 0.0; $dG = $dX; $dB = $dC; break;
			case($dH >= 4.0 && $dH < 5.0):
				$dR = $dX; $dG = 0.0; $dB = $dC; break;
			case($dH >= 5.0 && $dH < 6.0):
				$dR = $dC; $dG = 0.0; $dB = $dX; break;
			default:
				$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}

		$dM  = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;

		$rgb = array();
		$rgb[0] = round($dR);
		$rgb[1] = round($dG);
		$rgb[2] = round($dB);
		return $rgb;
	}

	function swackText($str) {
		$err = "";

		$fontFile = 'fonts/mini-wakuwaku-maru.otf';
		$fontSize = 12;
		
		// Retrieve bounding box:
		$typeSpace = imageftbbox($fontSize, 0, $fontFile, $str);

		// Determine image width and height, 10 pixels are added for 5 pixels padding:
		$imageWidth = abs($typeSpace[4] - $typeSpace[0]) + 10;
		$imageHeight = abs($typeSpace[5] - $typeSpace[1]) + 10;

		// Create image:
		$image = imagecreatetruecolor($imageWidth, $imageHeight);

		// Allocate text and background colors (RGB format):
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 75, 75, 75);

		// fill it with a bg color
		imagefill($image, 0, 0, $white);

		// shadow
		$y = $imageHeight - 8;
		for ($i = 0; $i < strlen($str); $i++) {
			// First, find the pixel length of the existing part thats drawn
			// Then draw the next character at existinglength
			$existingStr = substr($str, 0, $i);
			$charExSize = imageftbbox($fontSize, 0, $fontFile, $existingStr);
			$charExWidth = abs($charExSize[4] - $charExSize[0]) + 2;
			//$err .= $existingStr . ":".$charExWidth . "\r\n";

			$char = $str[$i];

			$yOff = sin($i) * 2;
			imagefttext($image, $fontSize, 0, 5 + $charExWidth, $y + $yOff + 2, $black, $fontFile, $char);
		}

		// main text
		for ($j = 1; $j < strlen($str)+1; $j++) { 
			$rgb = fGetRGB($j * 360 / (strlen($str)+1), 65, 100);
			$text_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);

			$existingStr = substr($str, 0, $j-1);
			$charExSize = imageftbbox($fontSize, 0, $fontFile, $existingStr);
			$charExWidth = abs($charExSize[4] - $charExSize[0]) + 2;
			//$err .= $existingStr . ":".$charExWidth . "\r\n";

			$char = $str[$j-1];

			$yOff = sin(($j-1)) * 2;

			imagefttext($image, $fontSize, 0, 5 + $charExWidth, $y + $yOff, $text_color, $fontFile, $char);
		}

		$filename = "img/".time().".png";
		imagepng($image, "img/".time().".png");
		imagedestroy($image);

		return $err . "\r\nhttp://braingale.org/slack/" . $filename;
	}