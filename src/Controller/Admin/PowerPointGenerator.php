<?php
// src/Service/PowerPointGeneratorService.php

namespace App\Controller\Admin;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Slide\Background\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color as FontColor;
use PhpOffice\PhpPresentation\Shape\RichText;

class PowerPointGeneratorService
{
    public function generatePresentation(array $data): string
    {
        $ppt = new PhpPresentation();

        // Create a slide
        $slide = $ppt->getActiveSlide();

        // Add title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(100)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(50);
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $titleShape->createTextRun('Credentials Data');
        $textRun->getFont()->setBold(true)
            ->setSize(32)
            ->setColor(new FontColor('FFE06B20'));

        // Add data to slide
        $offsetY = 150;
        foreach ($data as $key => $value) {
            $textShape = $slide->createRichTextShape()
                ->setHeight(40)
                ->setWidth(600)
                ->setOffsetX(170)
                ->setOffsetY($offsetY);
            $textRun = $textShape->createTextRun(ucwords($key) . ': ' . $value);
            $textRun->getFont()->setSize(20);
            $offsetY += 50; // Adjust spacing between lines
        }

        // Save the presentation
        $filename = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';
        $oWriter = IOFactory::createWriter($ppt, 'PowerPoint2007');
        $oWriter->save($filename);

        return $filename;
    }
}

/*namespace App\Controller\Admin;

class PowerPointGeneratorService
{
    public function generatePresentation(): string
    {
        // Your logic to generate PowerPoint presentation
        // Example logic to generate a simple PowerPoint presentation
        $phpPresentation = new \PhpOffice\PhpPresentation\PhpPresentation();
        $slide = $phpPresentation->getActiveSlide();
        $shape = $slide->createRichTextShape()
            ->setHeight(300)
            ->setWidth(600)
            ->setOffsetX(170)
            ->setOffsetY(180);
        $shape->getActiveParagraph()
            ->createTextRun('Hello World!')
            ->getFont()
            ->setSize(36);
        $writer = \PhpOffice\PhpPresentation\IOFactory::createWriter($phpPresentation, 'PowerPoint2007');
        $filename = 'hello.pptx';
        $writer->save($filename);
        return $filename;
    }
}*/
