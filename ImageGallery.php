<?php
    $images = array ('image1.png', 'image2.png', 'image3.png', 'image4.pmg');
    echo getImageGallery (1, $images);
    
    function getTheme ($index): array
    {
        $themes = array 
            (
                1 => array 
                    (
                        'index' => 'themes/1/pages/index.html',
                        'product_details' => 'themes/1/pages/product_details.html',
                        'image_rules' => array 
                            (
                                'active_class' => 'active', 
                                'per_div' => 3, 
                                'img_div' => '<div class="item {{active}}">{{image_list_}}</div>', 
                                'img' => '<img src="{{img_url_}}" {{display_}} alt="" width="85" height="84">'
                            ),
    				)
    		);
    	
    	return $themes[$index];
    }
    
    function getImageGallery ($index, $images): string
    {
        $div = $img_list = $div_list = '';
        $maxImages = 10;
        
        $rules = getTheme ($index);
        $imgDiv = $rules['image_rules']['img_div'];
        $imgPerDiv = $rules['image_rules']['per_div'];
        
        if ($imgPerDiv > 0)
        {
            $imagesIndex = 1;
            $splitImages = array_chunk($images, $imgPerDiv);
            for ($i = 1; $i <= sizeof($splitImages); $i++)
            {
                $divContent = $active = $imagesToInclude = '';
                if ($i == 1)
                {
                    $active = $rules['image_rules']['active_class'];
                }
                
                $divContent = str_replace('{{active}}', $active, $imgDiv);
                
                for ($x = 0; $x < $imgPerDiv; $x++)
                {
                    $imagesToInc = str_replace('{{img_url_}}', '{{img_url_'.$imagesIndex.'}}', $rules['image_rules']['img']);
                    $imagesToInc = str_replace('{{display_}}', '{{display_'.$imagesIndex.'}}', $imagesToInc);
                    $imagesToInclude .= $imagesToInc;
    
                    $imagesIndex++;
                }
                
                $divContent = str_replace('{{image_list_}}', '{{image_list_'.$i.'}}'.$imagesToInclude, $divContent);
                $div_list .= $divContent;
            }
            
            $newDiv = '';
            // replace with images
            for ($y = 1; $y <= $maxImages; $y++)
            {
                $div_list = str_replace('{{image_list_'.$y.'}}', '', $div_list);
                
                if (isset($images[$y-1]) && !empty($images[$y-1]))
                {
                    $div_list = str_replace('{{img_url_'.$y.'}}', $images[$y-1], $div_list);
                    $div_list = str_replace('{{display_'.$y.'}}', '', $div_list);
                }
                else
                {
                    $div_list = str_replace('{{img_url_'.$y.'}}', '', $div_list);
                    $div_list = str_replace('{{display_'.$y.'}}', 'style="display:none"', $div_list);
                }
                
            }
        }
        else
        {
            // for images that only need 1 div
        }
        
        return $div_list;
    }
?>
