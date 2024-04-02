<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
include("include/functions.php");


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 title">
            <h1><i class="fa fa-bars"></i> DASHBOARD</h1>
        </div>

        <div class="col-sm-12">
            <?php 
                echo failed_alert();
                echo success_alert();
            ?>
            <div class="content">
            <!-- ... rest of your HTML code ... -->
            <table class="table">
                <form action="creat-table.php" method="post">
                    <span>Add <input type="number" value="1" min="1" name="num_columns"> Column(s) <button type="submit" name="go">Go</button></span>
                </form>
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Length/Values</th>
                        <th scope="col">Default </th>
                        <th scope="col">Collation</th>
                        <th scope="col">Attributes</th>
                        <th scope="col">A_I</th>
                    </tr>
                </thead>
                <form action="creat-table.php" method="post">
                <tbody>
    <?php
    $numColumns = isset($_POST['go']) ? intval($_POST['num_columns']) : 4;
    for ($i = 0; $i < $numColumns; $i++) {
        echo '<tr>';
        echo '<th style="width: 15px;" scope="row"><input name="column_name[]" type="text"></th>';
        echo '<td>
            <select name="column_type[]">
                <option value="INT">INT</option>
                <option value="VARCHAR">VARCHAR</option>
                <option value="TEXT">TEXT</option>
                <option value="DATE">DATE</option>
            </select>
        </td>';
        echo '<td><input name="column_length[]" type="text"></td>';
        echo '<td>
            <select name="column_default_type[]">
                <option value="DEFAULT NULL">None</option>
                <option value="NULL">NULL</option>
                <option value="DEFAULT">As defined:</option>
                <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
            </select>
        </td>';
        echo '<td>
            <select name="collation[]">
                <option value=""></option>
                <option value="utf8_general_ci">utf8_general_ci</option>
                <option value="utf8_unicode_ci">utf8_unicode_ci</option>
                <!-- Add more valid collation options here -->
            </select>
        </td>';
        echo '<td>
            <select name="column_attributes[]">
                <option value=""></option>
                <option value="BINARY">BINARY</option>
                <option value="UNSIGNED">UNSIGNED</option>
                <option value="UNSIGNED ZEROFILL">UNSIGNED ZEROFILL</option>
                <option value="ON UPDATE CURRENT_TIMESTAMP">ON UPDATE CURRENT_TIMESTAMP</option>
                <option value="COMPRESSED=Zlib">COMPRESSED=Zlib</option>
            </select>
        </td>';
        echo '<td>';
        echo '<input value="AUTO_INCREMENT" name="column_auto_increment[]" type="checkbox">';
        echo '</td>';


        echo '</tr>';
    }
    ?>
</tbody>


                    <button type="submit" name="submit">Creat</button>
                </form>
            </table>
            <!-- ... rest of your HTML and form code ... -->
            <?php
            if (isset($_POST['submit'])) {
                // Collect user input for column details
                $column_names = $_POST['column_name'];
                $column_types = $_POST['column_type'];
                $column_lengths = $_POST['column_length'];
                $column_default_types = $_POST['column_default_type']; // Collect default value types
                $column_collations = $_POST['collation'];
                $column_attributes = $_POST['column_attributes'];
                $column_auto_increment = isset($_POST['column_auto_increment']) ? $_POST['column_auto_increment'] : array();

                // Collect the table name

                $column_definitions = array();
                for ($i = 0; $i < count($column_names); $i++) {
                    $column_definition = '`' . $column_names[$i] . '` ';
                
                    if ($column_auto_increment[$i] === 'AUTO_INCREMENT') {
                        // For AUTO_INCREMENT columns, use INT or BIGINT and make it primary key
                        $column_definition .= 'INT PRIMARY KEY AUTO_INCREMENT';
                    } else {
                        // Handle other column types and attributes
                        $column_definition .= $column_types[$i];
                
                        if ($column_types[$i] === 'VARCHAR') {
                            // Make sure to provide a valid length for VARCHAR
                            $column_definition .= '(' . intval($column_lengths[$i]) . ')';
                        }
                
                        // Handle nullable attribute
                        if (isset($column_default_types[$i]) && $column_default_types[$i] === 'on') {
                            $column_definition .= ' NULL';
                        } else {
                            $column_definition .= ' NOT NULL';
                        }
                    }
                
                    $column_definitions[] = $column_definition;
                }
                
                // Construct the CREATE TABLE query
                $columns_string = implode(', ', $column_definitions);
                $create_table_query = "CREATE TABLE `table_name` ($columns_string)";
                


                // Execute the CREATE TABLE query
                try {
                    $connect->exec($create_table_query);
                    echo "Table created successfully!";
                } catch (PDOException $e) {
                    echo "Error creating table: " . $e->getMessage();
                }
            }
            ?>
            </div>
            <?php include("include/footer.php"); ?>
        </div>
    </div>
</div>
</div>
