<?php
use CImage_Chart_Constant as Constant;

trait CImage_Chart_Concern_LabelDraw {
    /**
     * Draw a label box
     *
     * @param int    $x
     * @param int    $y
     * @param string $title
     * @param array  $captions
     * @param array  $format
     */
    public function drawLabelBox($x, $y, $title, array $captions, array $format = []) {
        $NoTitle = isset($format['noTitle']) ? $format['noTitle'] : null;
        $boxWidth = isset($format['boxWidth']) ? $format['boxWidth'] : 50;
        $drawSerieColor = isset($format['drawSerieColor']) ? $format['drawSerieColor'] : true;
        $serieBoxSize = isset($format['serieBoxSize']) ? $format['serieBoxSize'] : 6;
        $serieBoxSpacing = isset($format['serieBoxSpacing']) ? $format['serieBoxSpacing'] : 4;
        $verticalMargin = isset($format['verticalMargin']) ? $format['verticalMargin'] : 10;
        $horizontalMargin = isset($format['horizontalMargin']) ? $format['horizontalMargin'] : 8;
        $r = isset($format['r']) ? $format['r'] : $this->fontColorR;
        $g = isset($format['g']) ? $format['g'] : $this->fontColorG;
        $b = isset($format['b']) ? $format['b'] : $this->fontColorB;
        $fontName = isset($format['fontName']) ? $this->loadFont($format['fontName'], 'fonts') : $this->fontName;
        $fontSize = isset($format['fontSize']) ? $format['fontSize'] : $this->fontSize;
        $titleMode = isset($format['TitleMode']) ? $format['TitleMode'] : Constant::LABEL_TITLE_NOBACKGROUND;
        $titleR = isset($format['TitleR']) ? $format['TitleR'] : $r;
        $titleG = isset($format['TitleG']) ? $format['TitleG'] : $g;
        $titleB = isset($format['TitleB']) ? $format['TitleB'] : $b;
        $titleBackgroundR = isset($format['TitleBackgroundR']) ? $format['TitleBackgroundR'] : 0;
        $titleBackgroundG = isset($format['TitleBackgroundG']) ? $format['TitleBackgroundG'] : 0;
        $titleBackgroundB = isset($format['TitleBackgroundB']) ? $format['TitleBackgroundB'] : 0;
        $gradientStartR = isset($format['gradientStartR']) ? $format['gradientStartR'] : 255;
        $gradientStartG = isset($format['gradientStartG']) ? $format['gradientStartG'] : 255;
        $gradientStartB = isset($format['gradientStartB']) ? $format['gradientStartB'] : 255;
        $gradientEndR = isset($format['gradientEndR']) ? $format['gradientEndR'] : 220;
        $gradientEndG = isset($format['gradientEndG']) ? $format['gradientEndG'] : 220;
        $gradientEndB = isset($format['gradientEndB']) ? $format['gradientEndB'] : 220;
        $boxalpha = isset($format['boxalpha']) ? $format['boxalpha'] : 100;
        if (!$drawSerieColor) {
            $serieBoxSize = 0;
            $serieBoxSpacing = 0;
        }
        $txtPos = $this->getTextBox($x, $y, $fontName, $fontSize, 0, $title);
        $titleWidth = ($txtPos[1]['x'] - $txtPos[0]['x']) + $verticalMargin * 2;
        $titleHeight = ($txtPos[0]['y'] - $txtPos[2]['y']);
        if ($NoTitle) {
            $titleWidth = 0;
            $titleHeight = 0;
        }
        $captionWidth = 0;
        $captionHeight = -$horizontalMargin;
        foreach ($captions as $key => $caption) {
            $txtPos = $this->getTextBox(
                $x,
                $y,
                $fontName,
                $fontSize,
                0,
                $caption['caption']
            );
            $captionWidth = max(
                $captionWidth,
                ($txtPos[1]['x'] - $txtPos[0]['x']) + $verticalMargin * 2
            );
            $captionHeight = $captionHeight + max(($txtPos[0]['y'] - $txtPos[2]['y']), ($serieBoxSize + 2)) + $horizontalMargin;
        }
        if ($captionHeight <= 5) {
            $captionHeight = $captionHeight + $horizontalMargin / 2;
        }
        if ($drawSerieColor) {
            $captionWidth = $captionWidth + $serieBoxSize + $serieBoxSpacing;
        }
        $boxWidth = max($boxWidth, $titleWidth, $captionWidth);
        $xMin = $x - 5 - floor(($boxWidth - 10) / 2);
        $xMax = $x + 5 + floor(($boxWidth - 10) / 2);
        $restoreShadow = $this->shadow;
        if ($this->shadow == true) {
            $this->shadow = false;
            $poly = [];
            $poly[] = $x + $this->shadowX;
            $poly[] = $y + $this->shadowX;
            $poly[] = $x + 5 + $this->shadowX;
            $poly[] = $y - 5 + $this->shadowX;
            $poly[] = $xMax + $this->shadowX;
            $poly[] = $y - 5 + $this->shadowX;
            if ($NoTitle) {
                $poly[] = $xMax + $this->shadowX;
                $poly[] = $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2 + $this->shadowX;
                $poly[] = $xMin + $this->shadowX;
                $poly[] = $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2 + $this->shadowX;
            } else {
                $poly[] = $xMax + $this->shadowX;
                $poly[] = $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3 + $this->shadowX;
                $poly[] = $xMin + $this->shadowX;
                $poly[] = $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3 + $this->shadowX;
            }
            $poly[] = $xMin + $this->shadowX;
            $poly[] = $y - 5 + $this->shadowX;
            $poly[] = $x - 5 + $this->shadowX;
            $poly[] = $y - 5 + $this->shadowX;
            $this->drawPolygon(
                $poly,
                [
                    'r' => $this->shadowR,
                    'g' => $this->shadowG,
                    'b' => $this->shadowB,
                    'alpha' => $this->shadowA
                ]
            );
        }
        /* Draw the background */
        $gradientSettings = [
            'StartR' => $gradientStartR,
            'StartG' => $gradientStartG,
            'StartB' => $gradientStartB,
            'endR' => $gradientEndR,
            'endG' => $gradientEndG,
            'endB' => $gradientEndB,
            'alpha' => $boxalpha
        ];
        if ($NoTitle) {
            $this->drawGradientArea(
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMax,
                $y - 6,
                Constant::DIRECTION_VERTICAL,
                $gradientSettings
            );
        } else {
            $this->drawGradientArea(
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax,
                $y - 6,
                Constant::DIRECTION_VERTICAL,
                $gradientSettings
            );
        }
        $poly = [];
        $poly[] = $x;
        $poly[] = $y;
        $poly[] = $x - 5;
        $poly[] = $y - 5;
        $poly[] = $x + 5;
        $poly[] = $y - 5;
        $this->drawPolygon(
            $poly,
            [
                'r' => $gradientEndR,
                'g' => $gradientEndG,
                'b' => $gradientEndB,
                'alpha' => $boxalpha,
                'noBorder' => true
            ]
        );
        /* Outer border */
        $outerBorderColor = $this->allocateColor($this->picture, 100, 100, 100, $boxalpha);
        imageline($this->picture, $xMin, $y - 5, $x - 5, $y - 5, $outerBorderColor);
        imageline($this->picture, $x, $y, $x - 5, $y - 5, $outerBorderColor);
        imageline($this->picture, $x, $y, $x + 5, $y - 5, $outerBorderColor);
        imageline($this->picture, $x + 5, $y - 5, $xMax, $y - 5, $outerBorderColor);
        if ($NoTitle) {
            imageline(
                $this->picture,
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMin,
                $y - 5,
                $outerBorderColor
            );
            imageline(
                $this->picture,
                $xMax,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMax,
                $y - 5,
                $outerBorderColor
            );
            imageline(
                $this->picture,
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMax,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $outerBorderColor
            );
        } else {
            imageline(
                $this->picture,
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMin,
                $y - 5,
                $outerBorderColor
            );
            imageline(
                $this->picture,
                $xMax,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax,
                $y - 5,
                $outerBorderColor
            );
            imageline(
                $this->picture,
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $outerBorderColor
            );
        }
        /* Inner border */
        $InnerBorderColor = $this->allocateColor($this->picture, 255, 255, 255, $boxalpha);
        imageline($this->picture, $xMin + 1, $y - 6, $x - 5, $y - 6, $InnerBorderColor);
        imageline($this->picture, $x, $y - 1, $x - 5, $y - 6, $InnerBorderColor);
        imageline($this->picture, $x, $y - 1, $x + 5, $y - 6, $InnerBorderColor);
        imageline($this->picture, $x + 5, $y - 6, $xMax - 1, $y - 6, $InnerBorderColor);
        if ($NoTitle) {
            imageline(
                $this->picture,
                $xMin + 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMin + 1,
                $y - 6,
                $InnerBorderColor
            );
            imageline(
                $this->picture,
                $xMax - 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMax - 1,
                $y - 6,
                $InnerBorderColor
            );
            imageline(
                $this->picture,
                $xMin + 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $xMax - 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 2,
                $InnerBorderColor
            );
        } else {
            imageline(
                $this->picture,
                $xMin + 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMin + 1,
                $y - 6,
                $InnerBorderColor
            );
            imageline(
                $this->picture,
                $xMax - 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax - 1,
                $y - 6,
                $InnerBorderColor
            );
            imageline(
                $this->picture,
                $xMin + 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax - 1,
                $y - 4 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $InnerBorderColor
            );
        }
        /* Draw the separator line */
        if ($titleMode == Constant::LABEL_TITLE_NOBACKGROUND && !$NoTitle) {
            $yPos = $y - 7 - $captionHeight - $horizontalMargin - $horizontalMargin / 2;
            $xMargin = $verticalMargin / 2;
            $this->drawLine(
                $xMin + $xMargin,
                $yPos + 1,
                $xMax - $xMargin,
                $yPos + 1,
                [
                    'r' => $gradientEndR,
                    'g' => $gradientEndG,
                    'b' => $gradientEndB,
                    'alpha' => $boxalpha
                ]
            );
            $this->drawLine(
                $xMin + $xMargin,
                $yPos,
                $xMax - $xMargin,
                $yPos,
                [
                    'r' => $gradientStartR,
                    'g' => $gradientStartG,
                    'b' => $gradientStartB,
                    'alpha' => $boxalpha
                ]
            );
        } elseif ($titleMode == Constant::LABEL_TITLE_BACKGROUND) {
            $this->drawFilledRectangle(
                $xMin,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin * 3,
                $xMax,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin + $horizontalMargin / 2,
                [
                    'r' => $titleBackgroundR,
                    'g' => $titleBackgroundG,
                    'b' => $titleBackgroundB,
                    'alpha' => $boxalpha
                ]
            );
            imageline(
                $this->picture,
                $xMin + 1,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin + $horizontalMargin / 2 + 1,
                $xMax - 1,
                $y - 5 - $titleHeight - $captionHeight - $horizontalMargin + $horizontalMargin / 2 + 1,
                $InnerBorderColor
            );
        }
        /* Write the description */
        if (!$NoTitle) {
            $this->drawText(
                $xMin + $verticalMargin,
                $y - 7 - $captionHeight - $horizontalMargin * 2,
                $title,
                [
                    'align' => Constant::TEXT_ALIGN_BOTTOMLEFT,
                    'r' => $titleR,
                    'g' => $titleG,
                    'b' => $titleB
                ]
            );
        }
        /* Write the value */
        $yPos = $y - 5 - $horizontalMargin;
        $xPos = $xMin + $verticalMargin + $serieBoxSize + $serieBoxSpacing;
        foreach ($captions as $key => $caption) {
            $captionTxt = $caption['caption'];
            $txtPos = $this->getTextBox($xPos, $yPos, $fontName, $fontSize, 0, $captionTxt);
            $captionHeight = ($txtPos[0]['y'] - $txtPos[2]['y']);
            /* Write the serie color if needed */
            if ($drawSerieColor) {
                $boxSettings = [
                    'r' => $caption['format']['r'],
                    'g' => $caption['format']['g'],
                    'b' => $caption['format']['b'],
                    'alpha' => $caption['format']['alpha'],
                    'borderR' => 0,
                    'borderG' => 0,
                    'borderB' => 0
                ];
                $this->drawFilledRectangle(
                    $xMin + $verticalMargin,
                    $yPos - $serieBoxSize,
                    $xMin + $verticalMargin + $serieBoxSize,
                    $yPos,
                    $boxSettings
                );
            }
            $this->drawText($xPos, $yPos, $captionTxt, ['align' => Constant::TEXT_ALIGN_BOTTOMLEFT]);
            $yPos = $yPos - $captionHeight - $horizontalMargin;
        }
        $this->shadow = $restoreShadow;
    }

    /**
     * Write labels
     *
     * @param string $seriesName
     * @param array  $indexes
     * @param array  $format
     */
    public function writeLabel($seriesName, $indexes, array $format = []) {
        $overrideTitle = isset($format['overrideTitle']) ? $format['overrideTitle'] : null;
        $forceLabels = isset($format['forceLabels']) ? $format['forceLabels'] : null;
        $drawPoint = isset($format['drawPoint']) ? $format['drawPoint'] : Constant::LABEL_POINT_BOX;
        $drawVerticalLine = isset($format['drawVerticalLine']) ? $format['drawVerticalLine'] : false;
        $verticalLineR = isset($format['verticalLineR']) ? $format['verticalLineR'] : 0;
        $verticalLineG = isset($format['verticalLineG']) ? $format['verticalLineG'] : 0;
        $verticalLineB = isset($format['verticalLineB']) ? $format['verticalLineB'] : 0;
        $verticalLineAlpha = isset($format['verticalLineAlpha']) ? $format['verticalLineAlpha'] : 40;
        $verticalLineTicks = isset($format['verticalLineTicks']) ? $format['verticalLineTicks'] : 2;
        $data = $this->dataSet->getData();
        list($xMargin, $xDivs) = $this->scaleGetXSettings();
        if (!is_array($indexes)) {
            $index = $indexes;
            $indexes = [];
            $indexes[] = $index;
        }
        if (!is_array($seriesName)) {
            $serieName = $seriesName;
            $seriesName = [];
            $seriesName[] = $serieName;
        }
        if ($forceLabels != null && !is_array($forceLabels)) {
            $forceLabel = $forceLabels;
            $forceLabels = [];
            $forceLabels[] = $forceLabel;
        }
        foreach ($indexes as $key => $index) {
            $series = [];
            if ($data['orientation'] == Constant::SCALE_POS_LEFTRIGHT) {
                if ($xDivs == 0) {
                    $xStep = ($this->graphAreaX2 - $this->graphAreaX1) / 4;
                } else {
                    $xStep = ($this->graphAreaX2 - $this->graphAreaX1 - $xMargin * 2) / $xDivs;
                }
                $x = $this->graphAreaX1 + $xMargin + $index * $xStep;
                if ($drawVerticalLine) {
                    $this->drawLine(
                        $x,
                        $this->graphAreaY1 + $data['yMargin'],
                        $x,
                        $this->graphAreaY2 - $data['yMargin'],
                        [
                            'r' => $verticalLineR,
                            'g' => $verticalLineG,
                            'b' => $verticalLineB,
                            'alpha' => $verticalLineAlpha,
                            'ticks' => $verticalLineTicks
                        ]
                    );
                }
                $minY = $this->graphAreaY2;
                foreach ($seriesName as $serieName) {
                    if (isset($data['series'][$serieName]['data'][$index])) {
                        $axisID = $data['series'][$serieName]['axis'];
                        $xAxisMode = $data['xAxisDisplay'];
                        $xAxisFormat = $data['xAxisFormat'];
                        $xAxisUnit = $data['xAxisUnit'];
                        $axisMode = $data['axis'][$axisID]['display'];
                        $axisFormat = $data['axis'][$axisID]['format'];
                        $axisUnit = $data['axis'][$axisID]['unit'];
                        $xLabel = '';
                        if (isset($data['abscissa']) && isset($data['series'][$data['abscissa']]['data'][$index])
                        ) {
                            $xLabel = $this->scaleFormat(
                                $data['series'][$data['abscissa']]['data'][$index],
                                $xAxisMode,
                                $xAxisFormat,
                                $xAxisUnit
                            );
                        }
                        if ($overrideTitle != null) {
                            $description = $overrideTitle;
                        } elseif (count($seriesName) == 1) {
                            $description = $data['series'][$serieName]['description'] . ' - ' . $xLabel;
                        } elseif (isset($data['abscissa']) && isset($data['series'][$data['abscissa']]['data'][$index])
                        ) {
                            $description = $xLabel;
                        }
                        $serie = [
                            'r' => $data['series'][$serieName]['color']['r'],
                            'g' => $data['series'][$serieName]['color']['g'],
                            'b' => $data['series'][$serieName]['color']['b'],
                            'alpha' => $data['series'][$serieName]['color']['alpha']
                        ];
                        if (count($seriesName) == 1 && isset($data['series'][$serieName]['xOffset'])
                        ) {
                            $serieOffset = $data['series'][$serieName]['xOffset'];
                        } else {
                            $serieOffset = 0;
                        }
                        $value = $data['series'][$serieName]['data'][$index];
                        if ($value == Constant::VOID) {
                            $value = 'NaN';
                        }
                        if ($forceLabels != null) {
                            $Caption = isset($forceLabels[$key]) ? $forceLabels[$key] : 'Not set';
                        } else {
                            $Caption = $this->scaleFormat($value, $axisMode, $axisFormat, $axisUnit);
                        }
                        if ($this->lastChartLayout == Constant::CHART_LAST_LAYOUT_STACKED) {
                            if ($value >= 0) {
                                $lookFor = '+';
                            } else {
                                $lookFor = '-';
                            }
                            $value = 0;
                            $done = false;
                            foreach ($data['series'] as $name => $serieLookup) {
                                if ($serieLookup['isDrawable'] == true && $name != $data['abscissa'] && !$done
                                ) {
                                    if (isset($data['series'][$name]['data'][$index]) && $data['series'][$name]['data'][$index] != Constant::VOID
                                    ) {
                                        if ($data['series'][$name]['data'][$index] >= 0 && $lookFor == '+') {
                                            $value = $value + $data['series'][$name]['data'][$index];
                                        }
                                        if ($data['series'][$name]['data'][$index] < 0 && $lookFor == '-') {
                                            $value = $value - $data['series'][$name]['data'][$index];
                                        }
                                        if ($name == $serieName) {
                                            $done = true;
                                        }
                                    }
                                }
                            }
                        }
                        $x = floor($this->graphAreaX1 + $xMargin + $index * $xStep + $serieOffset);
                        $y = floor($this->scaleComputeY($value, ['axisID' => $axisID]));
                        if ($y < $minY) {
                            $minY = $y;
                        }
                        if ($drawPoint == Constant::LABEL_POINT_CIRCLE) {
                            $this->drawFilledCircle(
                                $x,
                                $y,
                                3,
                                [
                                    'r' => 255,
                                    'g' => 255,
                                    'b' => 255,
                                    'BorderR' => 0,
                                    'BorderG' => 0,
                                    'BorderB' => 0
                                ]
                            );
                        } elseif ($drawPoint == Constant::LABEL_POINT_BOX) {
                            $this->drawFilledRectangle(
                                $x - 2,
                                $y - 2,
                                $x + 2,
                                $y + 2,
                                [
                                    'r' => 255,
                                    'g' => 255,
                                    'b' => 255,
                                    'BorderR' => 0,
                                    'BorderG' => 0,
                                    'BorderB' => 0
                                ]
                            );
                        }
                        $series[] = ['format' => $serie, 'Caption' => $Caption];
                    }
                }
                $this->drawLabelBox($x, $minY - 3, $description, $series, $format);
            } else {
                if ($xDivs == 0) {
                    $xStep = ($this->graphAreaY2 - $this->graphAreaY1) / 4;
                } else {
                    $xStep = ($this->graphAreaY2 - $this->graphAreaY1 - $xMargin * 2) / $xDivs;
                }
                $y = $this->graphAreaY1 + $xMargin + $index * $xStep;
                if ($drawVerticalLine) {
                    $this->drawLine(
                        $this->graphAreaX1 + $data['yMargin'],
                        $y,
                        $this->graphAreaX2 - $data['yMargin'],
                        $y,
                        [
                            'r' => $verticalLineR,
                            'g' => $verticalLineG,
                            'b' => $verticalLineB,
                            'alpha' => $verticalLineAlpha,
                            'ticks' => $verticalLineTicks
                        ]
                    );
                }
                $minX = $this->graphAreaX2;
                foreach ($seriesName as $key => $serieName) {
                    if (isset($data['series'][$serieName]['data'][$index])) {
                        $axisID = $data['series'][$serieName]['axis'];
                        $xAxisMode = $data['xAxisDisplay'];
                        $xAxisFormat = $data['xAxisFormat'];
                        $xAxisUnit = $data['xAxisUnit'];
                        $axisMode = $data['axis'][$axisID]['display'];
                        $axisFormat = $data['axis'][$axisID]['format'];
                        $axisUnit = $data['axis'][$axisID]['unit'];
                        $xLabel = '';
                        if (isset($data['abscissa']) && isset($data['series'][$data['abscissa']]['data'][$index])
                        ) {
                            $xLabel = $this->scaleFormat(
                                $data['series'][$data['abscissa']]['data'][$index],
                                $xAxisMode,
                                $xAxisFormat,
                                $xAxisUnit
                            );
                        }
                        if ($overrideTitle != null) {
                            $description = $overrideTitle;
                        } elseif (count($seriesName) == 1) {
                            if (isset($data['abscissa']) && isset($data['series'][$data['abscissa']]['data'][$index])
                            ) {
                                $description = $data['series'][$serieName]['description'] . ' - ' . $xLabel;
                            }
                        } elseif (isset($data['abscissa']) && isset($data['series'][$data['abscissa']]['data'][$index])
                        ) {
                            $description = $xLabel;
                        }
                        $serie = [];
                        if (isset($data['extended']['palette'][$index])) {
                            $serie['r'] = $data['extended']['palette'][$index]['r'];
                            $serie['g'] = $data['extended']['palette'][$index]['g'];
                            $serie['b'] = $data['extended']['palette'][$index]['b'];
                            $serie['alpha'] = $data['extended']['palette'][$index]['alpha'];
                        } else {
                            $serie['r'] = $data['series'][$serieName]['color']['r'];
                            $serie['g'] = $data['series'][$serieName]['color']['g'];
                            $serie['b'] = $data['series'][$serieName]['color']['b'];
                            $serie['alpha'] = $data['series'][$serieName]['color']['alpha'];
                        }
                        if (count($seriesName) == 1 && isset($data['series'][$serieName]['xOffset'])) {
                            $serieOffset = $data['series'][$serieName]['xOffset'];
                        } else {
                            $serieOffset = 0;
                        }
                        $value = $data['series'][$serieName]['data'][$index];
                        if ($forceLabels != null) {
                            $Caption = isset($forceLabels[$key]) ? $forceLabels[$key] : 'Not set';
                        } else {
                            $Caption = $this->scaleFormat($value, $axisMode, $axisFormat, $axisUnit);
                        }
                        if ($value == Constant::VOID) {
                            $value = 'NaN';
                        }
                        if ($this->lastChartLayout == Constant::CHART_LAST_LAYOUT_STACKED) {
                            if ($value >= 0) {
                                $lookFor = '+';
                            } else {
                                $lookFor = '-';
                            }
                            $value = 0;
                            $done = false;
                            foreach ($data['series'] as $name => $serieLookup) {
                                if ($serieLookup['isDrawable'] == true && $name != $data['abscissa'] && !$done
                                ) {
                                    if (isset($data['series'][$name]['data'][$index]) && $data['series'][$name]['data'][$index] != Constant::VOID
                                    ) {
                                        if ($data['series'][$name]['data'][$index] >= 0 && $lookFor == '+') {
                                            $value = $value + $data['series'][$name]['data'][$index];
                                        }
                                        if ($data['series'][$name]['data'][$index] < 0 && $lookFor == '-') {
                                            $value = $value - $data['series'][$name]['data'][$index];
                                        }
                                        if ($name == $serieName) {
                                            $done = true;
                                        }
                                    }
                                }
                            }
                        }
                        $x = floor($this->scaleComputeY($value, ['axisID' => $axisID]));
                        $y = floor($this->graphAreaY1 + $xMargin + $index * $xStep + $serieOffset);
                        if ($x < $minX) {
                            $minX = $x;
                        }
                        if ($drawPoint == Constant::LABEL_POINT_CIRCLE) {
                            $this->drawFilledCircle(
                                $x,
                                $y,
                                3,
                                [
                                    'r' => 255,
                                    'g' => 255,
                                    'b' => 255,
                                    'BorderR' => 0,
                                    'BorderG' => 0,
                                    'BorderB' => 0
                                ]
                            );
                        } elseif ($drawPoint == Constant::LABEL_POINT_BOX) {
                            $this->drawFilledRectangle(
                                $x - 2,
                                $y - 2,
                                $x + 2,
                                $y + 2,
                                [
                                    'r' => 255,
                                    'g' => 255,
                                    'b' => 255,
                                    'BorderR' => 0,
                                    'BorderG' => 0,
                                    'BorderB' => 0
                                ]
                            );
                        }
                        $series[] = ['format' => $serie, 'Caption' => $Caption];
                    }
                }
                $this->drawLabelBox($minX, $y - 3, $description, $series, $format);
            }
        }
    }
}
