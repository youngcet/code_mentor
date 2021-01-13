<?php
    
    $select_query = 'SELECT {{FIELDS_TO_SELECT}} FROM table_name WHERE {{FIELDS_EQUAL}}';
    $my_update_query = 'UPDATE table_name SET {{UPDATE_FIELDS}} WHERE {{FIELDS_EQUAL}}';
    
    $fields = array ('name', 'surname', 'age');
    $value = array ('Cedric', 'Maenetja', 'Old Enough');
    
    $where_record_fields_are = array ('id', 'username');
    $where_record_values_are = array (1, 'cedricm');
    
    $select_results = execute_sql_select ($fields, $where_record_fields_are, $where_record_values_are, $select_query);
    
    $update_results = execute_sql_update ($fields, $value, $where_record_fields_are, $where_record_values_are, $my_update_query);
    
    // print results
    print_r($select_results);
    print_r($update_results);
    
    function execute_sql_select ($select_fields, $where_record_fields_are, $where_record_values_are, $query): array
    {
        try
        {
            $select_fields = implode (", ", $select_fields); // convert the array into a comma separated string
        
            // replace the fields in the query string
            $selectQuery = str_replace ('{{FIELDS_EQUAL}}', get_where_fields(mergeFields($where_record_fields_are, $where_record_values_are)), $query);
            $selectQuery = str_replace('{{FIELDS_TO_SELECT}}', $select_fields, $selectQuery);

            return array ('select_query' => $selectQuery);
        }
        catch (InvalidArraySizes $e)
        {
            return array ('error' => $e->getMessage());
        }
    }
        
    function execute_sql_update ($fields, $value, $where_record_fields_are, $where_record_values_are, $my_update_query): array
    {
        try
        {
            // replace the fields in the query string
            $update_query = str_replace ('{{UPDATE_FIELDS}}', get_update_fields(mergeFields($fields, $value)), $my_update_query);
            $update_query = str_replace ('{{FIELDS_EQUAL}}', get_where_fields(mergeFields($where_record_fields_are, $where_record_values_are)), $update_query);
            
            return array ('update_query' => $update_query);
        }
        catch (InvalidArraySizes $e)
        {
            return array ('error' => $e->getMessage());
        }
    }
        
    function mergeFields ($fields, $values): array
    {
        if (sizeof($fields) != sizeof($values)) throw new InvalidArraySizes('Arrays not same size');
        
        $mergedFields = array();
        $count = 0;
        
        // loop through the $fields and use as keys
        // use the index to get the value in $values array
        foreach ($fields as $field)
        {
            $mergedFields[trim($field)] = strip_tags($values[$count]);
            $count++;
        }
        
        return $mergedFields;
    }
    
    // this function subtitutes the fields and values and return the first part of the update query string
    // field1 = 'value1', field2 = 'field2', this will substitute this {{UPDATE_FIELDS}}
    function get_update_fields ($values): string
    {
        $final_query = '';
        
        foreach ($values as $key => $value)
        {
            if (!is_numeric($value))
            {
                $value = "'$value'";
            }
            
            $final_query .= "$key = $value,";
        }
        
        return rtrim ($final_query, ',');
    }
    
    // this function subtitutes the fields and values and return the where clause condition part of the query string
    // field1 = 'value1' AND field2 = 'field2', this will substitute {{FIELDS_EQUAL}}
    function get_where_fields ($values): string
    {
        $final_query = '';
        $count = 1;
        
        foreach ($values as $key => $value)
        {
            if (!is_numeric($value))
            {
                $value = "'$value'";
            }
            
            $key = $key;
            $value = $value;
            
            if ($count == sizeof($values))
            {
                $final_query .= "$key = $value";
            }
            else
            {
                // TODO: make operator dynamic
                $final_query .= "$key = $value AND ";
            }
            
            $count++;
        }
        
        return $final_query;
    }
?>
