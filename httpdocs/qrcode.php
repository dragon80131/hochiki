<?php

require './vendor/autoload.php';
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


$writer = new PngWriter();

// $qrCode = new QrCode('https://www.google.com/');

$qrCode = QrCode::create('https://yahoo.co.jp/')
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
    ->setSize(300)
    ->setMargin(10)
    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

$result = $writer->write($qrCode);
// header('Content-Type: '.$result->getMimeType());
$result->saveToFile(__DIR__.'/qrcode.png');

// echo $result->getString();
// $dataUri = $result->getDataUri();