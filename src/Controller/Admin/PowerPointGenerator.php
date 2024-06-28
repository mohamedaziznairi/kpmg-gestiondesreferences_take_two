<?php
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
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $titleShape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FF003366')); // Dark blue background
        $textRun = $titleShape->createTextRun('Our Credentials');
        $textRun->getFont()->setBold(true)
            ->setSize(36)
            ->setColor(new FontColor('FFFFFFFF'));  // White color for title text

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
        $leftBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF003366')); // Dark blue border
        $leftBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add credentials data to the left box
        foreach ($data as $key => $value) {
            if ($key !== 'objectives' && $key !== 'workstreams') {
                $textRun = $leftBox->createTextRun(ucwords(str_replace('_', ' ', $key)) . ': ' . $value . "\n");
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for text
            }
        }

        // Create title box for objectives
        $objectivesTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY);
        $objectivesTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $objectivesTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FF003366')); // Dark blue background

        // Add "Objectives" text to the objectives title box
        $textRun = $objectivesTitleBox->createTextRun("Objectives");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FFFFFFFF')); // White text

        // Create content box for objectives
        $objectivesBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight);
        $objectivesBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $objectivesBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add objectives to the objectives content box
        if (isset($data['objectives']) && is_array($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $objectiveText = $objective->getObjectif();
                $textRun = $objectivesBox->createTextRun("- " . $objectiveText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for objectives
            }
        }

        // Create title box for workstreams below objectives
        $workstreamsTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10); // 10 for a small gap
        $workstreamsTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $workstreamsTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FF003366')); // Dark blue background

        // Add "Workstreams" text to the workstreams title box
        $textRun = $workstreamsTitleBox->createTextRun("Workstreams");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FFFFFFFF')); // White text

        // Create content box for workstreams below workstreams title
        $workstreamsBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10 + $titleBoxHeight); // Positioned below workstreams title
        $workstreamsBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $workstreamsBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add workstreams to the workstreams content box
        if (isset($data['workstreams']) && is_array($data['workstreams'])) {
            foreach ($data['workstreams'] as $workstream) {
                $workstreamText = $workstream->getWorkstream();
                $textRun = $workstreamsBox->createTextRun("- " . $workstreamText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for workstreams
            }
        }

        // Save the presentation
        $filename = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';
        $oWriter = IOFactory::createWriter($ppt, 'PowerPoint2007');
        $oWriter->save($filename);

        return $filename;
    }
    public function generatePresentationvf(array $data): string
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
        $titleShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $titleShape->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('6D2077')); // Dark blue background
        $textRun = $titleShape->createTextRun('Nos Références');
        $textRun->getFont()->setBold(true)
            ->setSize(36)
            ->setColor(new FontColor('FFFFFFFF'));  // White color for title text

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
        $leftBox->getBorder()->setLineStyle(Border::LINE_SINGLE)->setColor(new FontColor('FF003366')); // Dark blue border
        $leftBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add credentials data to the left box
        foreach ($data as $key => $value) {
            if ($key !== 'objectives' && $key !== 'workstreams') {
                $textRun = $leftBox->createTextRun(ucwords(str_replace('_', ' ', $key)) . ': ' . $value . "\n");
                $textRun->getFont()->setSize(18)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for text
            }
        }

        // Create title box for objectives
        $objectivesTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY);
        $objectivesTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $objectivesTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FF003366')); // Dark blue background

        // Add "Objectives" text to the objectives title box
        $textRun = $objectivesTitleBox->createTextRun("Objectives");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FFFFFFFF')); // White text

        // Create content box for objectives
        $objectivesBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight);
        $objectivesBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $objectivesBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add objectives to the objectives content box
        if (isset($data['objectives']) && is_array($data['objectives'])) {
            foreach ($data['objectives'] as $objective) {
                $objectiveText = $objective->getObjectif();
                $textRun = $objectivesBox->createTextRun("- " . $objectiveText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for objectives
            }
        }

        // Create title box for workstreams below objectives
        $workstreamsTitleBox = $slide->createRichTextShape()
            ->setHeight($titleBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10); // 10 for a small gap
        $workstreamsTitleBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $workstreamsTitleBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FF003366')); // Dark blue background

        // Add "Workstreams" text to the workstreams title box
        $textRun = $workstreamsTitleBox->createTextRun("LIVRABLES");
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new FontColor('FFFFFFFF')); // White text

        // Create content box for workstreams below workstreams title
        $workstreamsBox = $slide->createRichTextShape()
            ->setHeight($contentBoxHeight)
            ->setWidth($rightBoxWidth)
            ->setOffsetX($rightBoxX)
            ->setOffsetY($rightBoxY + $titleBoxHeight + $contentBoxHeight + 10 + $titleBoxHeight); // Positioned below workstreams title
        $workstreamsBox->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $workstreamsBox->getFill()->setFillType(Fill::FILL_SOLID)->setStartColor(new FontColor('FFF0F0F0')); // Very light gray background

        // Add workstreams to the workstreams content box
        if (isset($data['workstreams']) && is_array($data['workstreams'])) {
            foreach ($data['workstreams'] as $workstream) {
                $workstreamText = $workstream->getWorkstream();
                $textRun = $workstreamsBox->createTextRun("- " . $workstreamText . "\n");
                $textRun->getFont()->setSize(16)
                    ->setColor(new FontColor('FF003366'));  // Dark blue for workstreams
            }
        }

        // Save the presentation
        $filename = tempnam(sys_get_temp_dir(), 'ppt_') . '.pptx';
        $oWriter = IOFactory::createWriter($ppt, 'PowerPoint2007');
        $oWriter->save($filename);

        return $filename;
    }
}
