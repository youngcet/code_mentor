<?php

    $str = '<html><p>Some parapgrah</p><%if ("{client.id}" == "1234")hello endif%>
    <p>Some parapgrah</p><%if ("{client.name}" == "Yung")hello there endif%></html>';
    $data = array 
        (
            'clients' => array 
                (
                    array ('{client.name}' => 'Yung Cet 1', '{client.id}' => '12341', '{client.balance}' => '10'),
                    array ('{client.name}' => 'Yung Cet 2', '{client.id}' => '12342', '{client.balance}' => '55'),
                    array ('{client.name}' => 'Yung Cet 3', '{client.id}' => '12343', '{client.balance}' => '-100'),
                    array ('{client.name}' => 'Yung Cet 4', '{client.id}' => '12344', '{client.balance}' => '0'),
                    array ('{client.name}' => 'Yung Cet 5', '{client.id}' => '12345', '{client.balance}' => '-60'),
                    array ('{client.name}' => 'Yung Cet 6', '{client.id}' => '12346', '{client.balance}' => '30'),
                    array ('{client.name}' => 'Yung Cet 7', '{client.id}' => '12347', '{client.balance}' => '30')
                ),
            'clients_test' => array 
                (
                    array ('{client.name}' => 'Yung Cet 1', '{client.id}' => '12341', '{client.balance}' => '10'),
                    array ('{client.name}' => 'Yung Cet 2', '{client.id}' => '12342', '{client.balance}' => '55'),
                    array ('{client.name}' => 'Yung Cet 3', '{client.id}' => '12343', '{client.balance}' => '-100'),
                    array ('{client.name}' => 'Yung Cet 4', '{client.id}' => '12344', '{client.balance}' => '0'),
                    array ('{client.name}' => 'Yung Cet 5', '{client.id}' => '12345', '{client.balance}' => '-60'),
                    array ('{client.name}' => 'Yung Cet 6', '{client.id}' => '12346', '{client.balance}' => '30'),
                    array ('{client.name}' => 'Yung Cet 7', '{client.id}' => '12347', '{client.balance}' => '30')
                ),
            'users' => array 
                (
                    array ('{users.name}' => 'Yung', '{users.desc}' => 'Cet'), 
                    array ('{users.name}' => 'Cedric', '{users.desc}' => 'Maenetja')
                ), 
            'products' => array 
                (
                    array ('{products.name}' => 'Product 1', '{products.desc}' => 'description'), 
                    array ('{products.name}' => 'Product 2', '{products.desc}' => 'description'),
                    array ('{products.name}' => 'Product 3', '{products.desc}' => 'description'), 
                    array ('{products.name}' => 'Product 4', '{products.desc}' => 'description')
                )
        );
    
    // $data = array ('{client.name}' => 'Yung', '{client.lname}' => 'Cet', '{client.id}' => '1234');
    // echo ExecuteÌfStatements ($str, $data);
    echo SubstListData (file_get_contents ('sample00.html'), $data);

    function ExecuteÌfStatements ($string, $data)
    {
        $charArray = array ('{' => '\{', '}' => '\}', '.' => '\.', '(' => '\(', ')' => '\)', '"' => '\"');

        if (preg_match_all ('/<%if (.*\))/', $string, $listsOfConditions))
        {
            for ($i = 0; $i < count ($listsOfConditions[0]); $i++)
            {
                $id = $listsOfConditions[1][0];
                $conditions = $listsOfConditions[0][0];
                //echo strpos ($string, $conditions)."\ns";
                
                for ($h = 0; $h < count ($listsOfConditions[0]); $h++)
                {
                    $htmlList = $listsOfConditions[0][$h];
                    $htmlList = SubstStringData ($htmlList, $charArray);
                    
                    if (preg_match ("/$htmlList([^\"]+?)endif%>/", $string, $listSection))
                    {
                        $conditionToExecute = SubstStringData ($htmlList, array ('<%if' => 'if', '\\' => '', '"' => '\''));
                        $conditionToExecute = SubstStringData ($conditionToExecute, $data)."return '$listSection[1]'";
                       
                        try
                        {
                            $text = (strpos ($conditionToExecute, '(\'{') === false) ? eval ("$conditionToExecute;") : '';
                        }
                        catch (Exception $e)
                        {
                            return sprintf ('failed to execute %s', $htmlList);
                        }
                       
                        $string = str_replace ($listSection[0], $text, $string);
                    }
                }
            }
        }

        return $string;
    }

    function SubstListData ($string, $data)
    {
        if (preg_match_all ('/<%=list dataset="([^"]+?)"/', $string, $listsInString))
        {
            for ($i = 0; $i < count ($listsInString[0]); $i++)
            {
                $id = $listsInString[1][0];
                $list = $listsInString[0][0];

                if (preg_match_all ("/$list/", $string, $totalListsById))
                {
                    if (count ($totalListsById[0]) > 1) die (sprintf ('Duplicate list id [%s]', $id));
                }
                
                if (! isset ($data[$id])) die (sprintf ('Array [%s] not defined', $id));
                
                for ($h = 0; $h < count ($listsInString[0]); $h++)
                {
                    $htmlList = $listsInString[0][$h];
                    $listContent = '';
                    
                    if (preg_match ("/$htmlList([^\"]+?)(.*)([^\"]+?)=%>/", $string, $listSection))
                    {
                        $conditionSection = $listSection[2].$listSection[3];
                        foreach (loadWithGenerator ($data[$listsInString[1][$h]]) as $dataArray)
                        {
                            $textToSubstitute = ExecuteÌfStatements ($conditionSection, $dataArray);
                            if (! empty ($textToSubstitute))
                            {
                                $listContent .= SubstStringData ($textToSubstitute, $dataArray);
                            }
                        }

                        $string = str_replace ($listSection[0], $listContent, $string);
                    }

                    if (preg_match ("/$htmlList([^\"]+?)=%>/", $string, $listSection))
                    {
                        if (! isset ($data[$listsInString[1][$h]])) die (sprintf ('list [%s] not found', $listsInString[1][$h]));
                       
                        foreach (loadWithGenerator ($data[$listsInString[1][$h]]) as $itemArray)
                        {
                            $listContent .= SubstStringData (trim ($listSection[1]), $itemArray);
                        }

                        $string = str_replace ($listSection[0], $listContent, $string);
                    }
                }
            }
        }

        return $string;
    }

    function SubstStringData ($templateString, $data)
    {
        if (! is_array ($data)) return $templateString;
        
        foreach (loadWithGenerator ($data) as $key => $value)
        {
            if (is_array ($value)) continue;
            $templateString = str_replace ($key, $value, $templateString);
        }

        # return the substituted string
        return $templateString;
    }

    function loadWithGenerator ($arr)
    {
        foreach ($arr as $key => $value)
        {
            yield $key => $value;
        }
    }

?>