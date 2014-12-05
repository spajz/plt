<?php
return array(
        'options' => array(
                'default' => array(
                        'engine' => 'bing',
                        'type' => 'Images',
                        'videoSize' => array(
                                'width' => 640,
                                'height' => 360,
                        )
                ),
                'engine' => array(
                        'bing' => 'Bing',
                        'google' => 'Google',

                ),
                'type' => array(
                       // 'Web' => 'Web',
                        'Images' => 'Images',
                        'Videos' => 'Videos',
                    //'news' => 'News',
                    //'spell' => 'Spell',
                ),
                'videoSites' => array( // site:metacafe.com cars
                        '' => '* All',
                        'youtube' => 'youtube.com',
                        'metacafe' => 'metacafe.com',
                        'vimeo' => 'vimeo.com',
                        'dailymotion' => 'dailymotion.com'
                ),
                'embedUrl' => array(
                        'youtube' => 'youtube.com',
                        'metacafe' => 'metacafe.com',
                        'vimeo' => 'vimeo.com',
                        'dailymotion' => 'dailymotion.com'
                ),

        ),
        'google' => array(
              //  'apiKey' => 'AIzaSyCc-7egw6-Zwu2evPDPyKMiKYWo2L-gUpg',
            'apiKey' => 'AIzaSyDgvUrYq4E4RpErXWMFdjNSVRKZtJSaWlI',
                'searchType' => array(
                        'web' => '011459340096687978320:evr5qey-h6w',
                    //'web' => '008798753386113473976:a4ds4l9nxbg', // eximius
                       // 'videos' => '011459340096687978320:cb-ijcajam8',
                     //   'videos' => '011459340096687978320:_psb0l-uki8', // all videos
                    //'videos' => '011459340096687978320:ted5mclsnp4', // metacafe
                    //'videos' => '011459340096687978320:lt2xr2bbofc', // dailymotion
                        'allVideos' => '011459340096687978320:_psb0l-uki8',
                ),
            // https://support.google.com/webmasters/answer/35287?hl=en
                'fileType' => array(
                        '' => '* All',
                        'swf' => 'swf - Adobe Flash',
                        'pdf' => 'pdf - Adobe Portable',
                        'ps' => 'ps - Adobe PostScript',
                        'dwf' => 'dwf - Autodesk Design Web Format',
                        'kml' => 'kml - Google Earth',
                        'kmz' => 'kmz - Google Earth',
                        'gpx' => 'gpx - GPS eXchange Format',
                        'hwp' => 'hwp - Hancom Hanword',
                        'htm' => 'htm - HTML',
                        'html' => 'html - HTML',
                        'xls' => 'xls - Microsoft Excel',
                        'xlsx' => 'xlsx - Microsoft Excel',
                        'ppt' => 'ppt - Microsoft PowerPoint',
                        'pptx' => 'pptx - Microsoft PowerPoint',
                        'doc' => 'doc - Microsoft Word',
                        'docx' => 'docx - Microsoft Word',
                        'odp' => 'odp - OpenOffice presentation',
                        'ods' => 'ods - OpenOffice spreadsheet',
                        'odt' => 'odp - OpenOffice text',
                        'rtf' => 'rtf - Rich Text Format',
                        'wri' => 'wri - Rich Text Format',
                        'svg' => 'svg - Scalable Vector Graphics',
                        'tex' => 'tex - TeX / LaTeX',
                        'txt' => 'txt - Text',
                        'bas' => 'bas - Basic source code',
                        'c' => 'c - C / C++ source code',
                        'cc' => 'cc - C / C++ source code',
                        'cpp' => 'cpp - C / C++ source code',
                        'cxx' => 'cxx - C / C++ source code',
                        'h' => 'h - C / C++ source code',
                        'hpp' => 'hpp - C / C++ source code',
                        'cs' => 'cs - C# source code',
                        'pl' => 'pl - Perl source code',
                        'py' => 'py - Python source code',
                        'wml' => 'wml - Wireless Markup Language',
                        'wap' => 'wap - Wireless Markup Language',
                        'java' => 'java - Java source code',
                        'xml' => 'xml - XML',
                ),
                'imgSize' => array(
                        '' => '* All',
                        'huge' => 'Huge',
                        'icon' => 'Icon',
                        'large' => 'Large',
                        'medium' => 'Medium',
                        'small' => 'Small',
                        'xlarge' => 'Xlarge',
                        'xxlarge' => 'Xxlarge',
                ),
                'imgType' => array(
                        '' => '* All',
                        'clipart' => 'Clipart',
                        'face' => 'Face',
                        'lineart' => 'Lineart',
                        'news' => 'News',
                        'photo' => 'Photo',
                ),
                'lr' => array(
                        '' => '* All',
                        'lang_ar' => 'Arabic',
                        'lang_bg' => 'Bulgarian',
                        'lang_ca' => 'Catalan',
                        'lang_cs' => 'Czech',
                        'lang_da' => 'Danish',
                        'lang_de' => 'German',
                        'lang_el' => 'Greek',
                        'lang_en' => 'English',
                        'lang_es' => 'Spanish',
                        'lang_et' => 'Estonian',
                        'lang_fi' => 'Finnish',
                        'lang_fr' => 'French',
                        'lang_hr' => 'Croatian',
                        'lang_hu' => 'Hungarian',
                        'lang_id' => 'Indonesian',
                        'lang_is' => 'Icelandic',
                        'lang_it' => 'Italian',
                        'lang_iw' => 'Hebrew',
                        'lang_ja' => 'Japanese',
                        'lang_ko' => 'Korean',
                        'lang_lt' => 'Lithuanian',
                        'lang_lv' => 'Latvian',
                        'lang_nl' => 'Dutch',
                        'lang_no' => 'Norwegian',
                        'lang_pl' => 'Polish',
                        'lang_pt' => 'Portuguese',
                        'lang_ro' => 'Romanian',
                        'lang_ru' => 'Russian',
                        'lang_sk' => 'Slovak',
                        'lang_sl' => 'Slovenian',
                        'lang_sr' => 'Serbian',
                        'lang_sv' => 'Swedish',
                        'lang_tr' => 'Turkish',
                        'lang_zh-CN' => 'Chinese (Simplified)',
                        'lang_zh-TW' => 'Chinese (Traditional)',
                ),
        ),

        'bing' => array(
                'baseUrl' => 'https://api.datamarket.azure.com/Bing/Search/',

                'apiKey' => 'nDuU303K2pNtJZmKVRmhAzAfrdquvRqLF0SHMkUE99M',

                'imgSize' => array(
                        '' => '* All',
                        'Small' => 'Small',
                        'Medium' => 'Medium',
                        'Large' => 'Large',
                ),
                'imgDimension' => array(
                        '' => '* All',
                        'Width' => 'Width',
                        'Height' => 'Height',
                ),
                'imgAspect' => array(
                        '' => '* All',
                        'Square' => 'Square',
                        'Wide' => 'Wide',
                        'Tall' => 'Tall',
                ),
                'imgType' => array(
                        '' => '* All',
                        'Photo' => 'Photo',
                        'Graphics' => 'Graphics',
                        'Face' => 'Face',
                        'Portrait' => 'Portrait',
                ),
        ),
);
 
 