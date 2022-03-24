<?php

    // our html file
    $str = file_get_contents('sample.html');

    // our application data
    // this could be data from db or a web service
    // note that the nested arrays map to the dataset value in the html file
    $data = array 
        (
            'clients' => array 
                (
                    array ('{clients.name}' => 'Yung', '{clients.lname}' => 'Cet')
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
    
    echo SubstStringData (SubstListData ($str, $data), array ('{users.table_title}' => 'Users', '{users.list_title}' => 'Users', '{products.list_title}' => 'Products'));

    /* 
        this function takes a string and an array as arguments
        $string - the string to substitute values in
        $data - the data to substitute

        Validations:
        - check for duplicate dataset values in the string
        - check that the data array with the given dataset value exists
        
        The function will look for and extract all sections in the string that match <%=list dataset="" and add them to an array 
        regex string = '/<%=list dataset="([^"]+?)"/'
        Array
            (
                [0] => <%=list dataset="clients"
                [1] => <%=list dataset="users"
                [2] => <%=list dataset="products"
            )
        
        After extracting the sections, the function loops through the array and extracts the section enclosed between <%=list dataset="clients" =%>
        We also get the dataset value which is the key for the data array (we will need this to get the data from the data array when we populating the section)
        In this example, the dataset is 'clients'
        regex string = '/<%=list dataset="([^\"]+?)=%>/'
        <tr>
            <td>{clients.name}</td>
            <td>{clients.lname}</td>
        </tr>

        An example of the list section:
        <li>{users.name} {users.desc}</li>
        
        Now that we have the section, we can start populating the data. We pass the key (clients) to the data array to get the data we need and loop through that array
        'clients' => array 
            (
                array ('{clients.name}' => 'Yung', '{clients.lname}' => 'Cet')
            ),
        
        We simply replace '{clients.name}' and '{clients.lname}' in the string with the data from the array since the array contains such keys. After looping through this 
        'clients' array we get:
        <tr>
            <td>Yung</td>
            <td>Cet</td>
        </tr>

        We then continue looping through the rest of this array replacing the values in each section
        Array
            (
                [0] => <%=list dataset="clients"
                [1] => <%=list dataset="users"
                [2] => <%=list dataset="products"
            )
        
        At the end we return the substituted string
        
    */

    function SubstListData ($string, $data)
    {
        if (preg_match_all ('/<%=list dataset="([^"]+?)"/', $string, $listsInString))
        {
            for ($i = 0; $i < count ($listsInString[0]); $i++)
            {
                $id = $listsInString[1][0];
                $list = $listsInString[0][1];

                if (preg_match_all ("/$list/", $string, $totalListsById))
                {
                    if (count ($totalListsById[0]) > 1) die (sprintf ('Duplicate list id [%s]', $id));
                }
                
                if (! isset ($data[$id])) die (sprintf ('Array [%s] not defined', $id));
                
                for ($h = 0; $h < count ($listsInString[0]); $h++)
                {
                    $htmlList = $listsInString[0][$h];
                    $listContent = '';
                    if (preg_match ("/$htmlList([^\"]+?)=%>/", $string, $listSection))
                    {
                        if (! isset ($data[$listsInString[1][$h]])) die (sprintf ('list [%s] not found', $listsInString[1][$h]));
                        print_r($listSection[1]);
                        die;
                        foreach (loadWithGenerator ($data[$listsInString[1][$h]]) as $itemArray)
                        {
                            $listContent .= SubstStringData (trim ($listSection[1]), $itemArray);
                        }

                        $string = str_replace ($listSection[0], $listContent, $string);
                    }
                }
            }

            return $string;
        }
    }

    /*
        This function simply replaces specific text from the string with the data from the given array
        $templateString - the string to substitute values in
        $data - the data to substitute
    */

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

     /*
        This is a generator to allow us to go through bigger arrays to avoid hogging or our code hanging during execution
    */

    function loadWithGenerator ($arr)
    {
        foreach ($arr as $key => $value)
        {
            yield $key => $value;
        }
    }
?>