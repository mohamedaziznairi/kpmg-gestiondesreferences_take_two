<?php
// src/Service/PowerPointGeneratorService.php
namespace App\Controller\Admin;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color as FontColor;
use PhpOffice\PhpPresentation\Style\Border;
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

        // Define offsets and box sizes
        $leftBoxX = 50;
        $leftBoxY = 120;
        $leftBoxWidth = 350;
        $leftBoxHeight = 500;

        $rightBoxX = 450;
        $rightBoxY = 120;
        $rightBoxWidth = 450;
        $titleBoxHeight = 40; // Height for the title boxes
        $contentBoxHeight = 200; // Height for the content boxes

        // Create left box for credentials
        $leftBox = $slide->createRichTextShape()
            ->setHeight($leftBoxHeight)
            ->setWidth($leftBoxWidth)
            ->setOffsetX($leftBoxX)
            ->setOffsetY($leftBoxY);
        $leftBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $leftBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF000000')); // Black border
        $leftBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFFFFFFF')); // White background

        // Add credentials data to the left box
        foreach ($data as $key => $value) {
            if ($key !== 'objectives' && $key !== 'Workstreams') {
                $textRun = $leftBox->createTextRun(ucwords(str_replace('_', ' ', $key)) . ': ' . $value . "\n");
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF333333'));  // Dark gray color for text
            }
        }

        // Create title box for objectives
        $objectivesTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY);
        $objectivesTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $objectivesTitleBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF000000')); // Black border
        $objectivesTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFE0E0E0')); // Light gray background

        // Add "Objectives" text to the objectives title box
        $textRun = $objectivesTitleBox->createTextRun("Objectives");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FF333333'));

        // Create content box for objectives
        $objectivesBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight);
        $objectivesBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $objectivesBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF000000')); // Black border
        $objectivesBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFFFFFFF')); // White background

        // Add objectives to the objectives content box
        if (isset($data['objectives']) && is_array($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $objectiveText = $objective->getObjectif();
                $textRun = $objectivesBox->createTextRun("- " . $objectiveText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF666666'));  // Slightly lighter gray for objectives
            }
        }

        // Create title box for workstreams below objectives
        $workstreamsTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10); // 10 for a small gap
        $workstreamsTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $workstreamsTitleBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF000000')); // Black border
        $workstreamsTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFE0E0E0')); // Light gray background

        // Add "Workstreams" text to the workstreams title box
        $textRun = $workstreamsTitleBox->createTextRun("Workstreams");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FF333333'));

        // Create content box for workstreams below workstreams title
        $workstreamsBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10 + $titleBoxHeight); // Positioned below workstreams title
        $workstreamsBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $workstreamsBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF000000')); // Black border
        $workstreamsBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFFFFFFF')); // White background

        // Add workstreams to the workstreams content box
        if (isset($data['Workstreams']) && is_array($data['Workstreams'])) {
            foreach ($data['Workstreams'] as $workstream) {
                $workstreamText = $workstream->getWorkstream();
                $textRun = $workstreamsBox->createTextRun("- " . $workstreamText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF666666'));  // Slightly lighter gray for workstreams
            }
        }

        // Save the presentation
        $filename = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';
        $oWriter = IOFactory::createWriter($ppt, 'PowerPoint2007');
        $oWriter->save($filename);

        return $filename;
    }
}



// src/Service/PowerPointGeneratorService.php
/*namespace App\Controller\Admin;

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
                $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
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
                    $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $textRun = $textShape->createTextRun("- " . $objectiveText);
                    $textRun->getFont()->setSize(16)
                        ->setColor(new FontColor('FF666666'));  // Slightly lighter gray for objectives
                    $offsetY += 30; // Adjust spacing between objectives
                }
            } 
             else if ($key === 'Workstreams' && is_array($value)) {
                $textShape = $slide->createRichTextShape()
                    ->setHeight(30)
                    ->setWidth(700)
                    ->setOffsetX(50)
                    ->setOffsetY($offsetY);
                $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $textRun = $textShape->createTextRun("Workstreams:");
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF333333'));  // Dark gray color for text
                $offsetY += 40; // Adjust spacing after "Objectives" header

                foreach ($value as $workstream) {
                    // Ensure 'getObjectif()' returns the objective text
                    $workstreamText = $workstream->getWorkstream();
                    $textShape = $slide->createRichTextShape()
                        ->setHeight(30)
                        ->setWidth(700)
                        ->setOffsetX(50)
                        ->setOffsetY($offsetY);
                    $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $textRun = $textShape->createTextRun("- " . $workstreamText);
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
}*/
