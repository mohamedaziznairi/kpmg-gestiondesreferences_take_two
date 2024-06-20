<?php
// src/Service/PowerPointGeneratorService.php
namespace App\Controller\Admin;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Slide\Background\Color as BackgroundColor;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color as FontColor;
use PhpOffice\PhpPresentation\Style\Fill;

class PowerPointGeneratorService
{
    public function generatePresentation(array $data): string
    {
        $ppt = new PhpPresentation();

        // Create a slide with title and background color
        $slide = $ppt->getActiveSlide();

        // Add title
        $titleShape = $slide->createRichTextShape()
            ->setHeight(60)
            ->setWidth(700)
            ->setOffsetX(50)
            ->setOffsetY(30);
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $textRun = $titleShape->createTextRun('Our Credentials');
        $textRun->getFont()->setBold(true)
            ->setSize(36)
            ->setColor(new FontColor('FF003366'));  // Dark blue color for title text

        // Add data to slide
        $offsetY = 120;
        foreach ($data as $key => $value) {
            // Special handling for 'objectives'
            if ($key === 'objectives' && is_array($value)) {
                $textShape = $slide->createRichTextShape()
                    ->setHeight(30)
                    ->setWidth(700)
                    ->setOffsetX(50)
                    ->setOffsetY($offsetY);
                $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $textRun = $textShape->createTextRun("Objectives:");
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF333333'));  // Dark gray color for text
                $offsetY += 40; // Adjust spacing after "Objectives" header

                foreach ($value as $objective) {
                    // Ensure 'getObjectif()' returns the objective text
                    $objectiveText = $objective->getObjectif();
                    $textShape = $slide->createRichTextShape()
                        ->setHeight(30)
                        ->setWidth(700)
                        ->setOffsetX(50)
                        ->setOffsetY($offsetY);
                    $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $textRun = $textShape->createTextRun("- " . $objectiveText);
                    $textRun->getFont()->setSize(16)
                        ->setColor(new FontColor('FF666666'));  // Slightly lighter gray for objectives
                    $offsetY += 30; // Adjust spacing between objectives
                }
            } else {
                $textShape = $slide->createRichTextShape()
                    ->setHeight(30)
                    ->setWidth(700)
                    ->setOffsetX(50)
                    ->setOffsetY($offsetY);
                $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $textRun = $textShape->createTextRun(ucwords(str_replace('_', ' ', $key)) . ': ' . $value);
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF333333'));  // Dark gray color for text
                $offsetY += 40; // Adjust spacing between lines
            }
        }

        // Save the presentation
        $filename = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';
        $oWriter = IOFactory::createWriter($ppt, 'PowerPoint2007');
        $oWriter->save($filename);

        return $filename;
    }
}
