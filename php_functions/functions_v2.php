<?php

function addtodb_v2($tb, $fds, $bind_vals): mixed
{
    global $pdo;  // Assuming $pdo is a PDO connection

    try {
        // Create an array of placeholders equal to the number of values
        $placeholders = array_fill(0, count($bind_vals), '?');

        // Combine field names and placeholders
        $fields = implode(',', $fds);
        $placeholders = implode(',', $placeholders);

        $insertq = "INSERT INTO $tb ($fields) VALUES ($placeholders)";

        // Prepare the statement
        $stmt = $pdo->prepare($insertq);

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
        }

        // types
        $bind_types = get_vals_types($bind_vals);

        // Bind values to the prepared statement
        for ($i = 0; $i < count($bind_vals); $i++) {
            $stmt->bindValue($i + 1, $bind_vals[$i], $bind_types[$i]);
        }

        // Execute the prepared statement
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        // Close the statement
        $stmt->closeCursor();

        return 1;
    } catch (Exception $e) {
        // Handle the exception (e.g., log the error or perform error-specific actions)
        return $e->getMessage();
    }
}

function updatedb_v2($tb, $fds, $where, $vals): mixed
{
    global $pdo;  // Assuming $pdo is a PDO connection

    // Build the SQL query
    $updateQuery = "UPDATE $tb SET $fds WHERE $where";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($updateQuery);

        if ($stmt) {
            // types
            $bind_types = get_vals_types($vals);

            // Bind all values at once
            for ($i = 0; $i < count($vals); $i++) {
                $stmt->bindValue($i + 1, $vals[$i], $bind_types[$i]);
            }

            // Execute the prepared statement
            if ($stmt->execute()) {
                // logupdate($tb, $updateQuery);
                $stmt->closeCursor();
                return 1;
            } else {
                throw new Exception($stmt->errorInfo()[2]);
            }
        } else {
            throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
        }
    } catch (Exception $e) {
        // Handle the exception (e.g., log it, show an error message)
        // You can customize this part based on your error handling needs.
        // error_log("Error in updatedb: " . $e->getMessage());
        return $e->getMessage();
    }
}



function fetchrow_v2($table, $where, $bind_vals, $fd): mixed
{
    global $pdo;  // Assuming $pdo is a PDO connection

    $query = "SELECT $fd FROM $table WHERE $where ORDER BY uid DESC";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($query);

        if ($stmt) {
            // bind types
            $bind_types = get_vals_types($bind_vals);

            // Bind parameters
            for ($i = 0; $i < count($bind_vals); $i++) {
                $stmt->bindValue($i + 1, $bind_vals[$i], $bind_types[$i]);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Close the statement
            $stmt->closeCursor();

            return $result[$fd];
        } else {
            // Handle the error if the prepare statement fails
            throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
        }
    } catch (Exception $e) {
        // Handle the exception
        // You can log or return an error message as needed
        return null;
    }
}


function checkrowexists_v2($table, $where, $bind_vals): mixed
{
    global $pdo;  // Assuming $pdo is a PDO connection
    $payload = null;
    $message = null;

    // Construct the SQL query with the provided conditions
    $query = "SELECT * FROM $table WHERE $where";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($query);

        if ($stmt) {
            // Bind parameters to the prepared statement
            if (!empty($bind_vals)) {
                // bind types
                $bind_types = get_vals_types($bind_vals);

                // binding
                for ($i = 0; $i < count($bind_vals); $i++) {
                    $stmt->bindValue($i + 1, $bind_vals[$i], $bind_types[$i]);
                }
            }

            // Execute the statement
            $stmt->execute();

            // Store the result
            $stmt->store_result();

            // Get the number of rows
            $totalrows = $stmt->num_rows;

            // Close the statement
            $stmt->closeCursor();

            if ($totalrows > 0) {
                return 1;
            } else {
                return 0;
            }
        } else {
            // Throw an exception for statement preparation failure
            throw new Exception("Error preparing statement: " . $pdo->errorInfo()[2]);
        }
    } catch (Exception $e) {
        // Handle the exception (you can log or display an error message)
        echo $e->getMessage();
        return 0;
    }
}



function validatetoken_v2($token): int
{

    global $fulldate;

    $where_tkn = "token = ? AND status = ? AND expiry_date >= ?";
    $tkn_vals = ["$token", 1, "$fulldate"];

    $token_valid = checkrowexists_v2("o_tokens", $where_tkn, $tkn_vals);
    if ($token_valid == 1) {
        return 1;
    } else {
        return 0;
    }
}

function fetchtable_v2($table, $category, $orderby, $dir, $limit, $fds = '*')
{
    global $pdo;  // Assuming $pdo is a PDO connection

    $query = "SELECT $fds FROM $table WHERE $category ORDER BY $orderby $dir LIMIT :limit";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($query);

        if (!$stmt) {
            throw new Exception("Error in preparing statement: " . $pdo->errorInfo()[2]);
        }

        // Bind the limit parameter
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
        }

        // Fetch the result
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (Exception $e) {
        // Handle the exception
        echo "An error occurred: " . $e->getMessage();
        // You can choose to log the error or perform other error handling tasks here.
        return null; // Return false or a suitable default value to indicate failure.
    }
}


function table_to_array_v2($tbl, $where, $limit, $fld, $orderby='uid', $dir='asc'){
    $res_array = array();
    $recs = fetchtable($tbl,$where, $orderby, $dir, "$limit", "$fld");
    while($r = mysqli_fetch_array($recs))
    {
        $value = $r[$fld];
       array_push($res_array, $value);
    }
    return $res_array;
}

function fetchonerow_v2($table, $where, $bind_vals, $fds = '*'): mixed
{
    global $pdo;  // Assuming $pdo is a PDO connection

    $query = "SELECT $fds FROM $table WHERE ($where) ORDER BY uid DESC";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($query);

        if (!$stmt) {
            throw new Exception("Error in preparing statement: " . $pdo->errorInfo()[2]);
        }

        // bind types
        $bind_types = get_vals_types($bind_vals);

        // Bind parameters
        for ($i = 0; $i < count($bind_vals); $i++) {
            $stmt->bindValue($i + 1, $bind_vals[$i], $bind_types[$i]);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
        }

        // Fetch one row as an associative array
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close the statement
        $stmt->closeCursor();

        return $row;
    } catch (Exception $e) {
        // Handle the exception
        echo "An error occurred: " . $e->getMessage();
        // You can choose to log the error or perform other error handling tasks here.
        return null; // Return null or a suitable default value to indicate failure.
    }
}


function session_details_v2(): mixed
{
    $userd = array();
    if (isset($_SESSION['o-token'])) {
        $token = $_SESSION['o-token'];
        $valid = validatetoken_v2($token);
        if ($valid == 0) {
            header("location:login");
            return null;
        } else {
            $where_tkn = "token = ?";
            $tkn_vals = ["$token"];
            $token_user = fetchrow_v2('o_tokens', $where_tkn, $tkn_vals, "userid");

            $where_user_tkn = "uid = ?";
            $user_tkn_vals = ["$token_user"];
            $userd = fetchonerow_v2('o_users', $where_user_tkn, $user_tkn_vals, "*");
        }
    } else {
        return null;
    }
    return $userd;
}

function permission_v2($user_id, $tbl, $rec, $act): mixed
{
    if (isNotArrayOrObject($rec)) {
        $rec = intval($rec);
    } else {
        $rec = 0;
    }

    $where_usr_grp = "uid = ?";
    $usr_grp_vals = [$user_id];
    $user_group = fetchrow_v2('o_users', $where_usr_grp, $usr_grp_vals, "user_group");
    if ($user_group == 1) {
        return  1;
    } else {
        $where_permi = "(group_id = ? OR user_id = ?) AND tbl = ? AND rec = ?, AND $act = ?";
        $permi_vals = [$user_group, $user_id, "$tbl", $rec, 1];
        return checkrowexists_v2('o_permissions', $where_permi, $permi_vals);
    }
}

function store_event_v2($tbl, $fld, $event_details): void
{
    global $fulldate;
    $ses = session_details_v2();
    $event_by = $ses['uid'] ?? 0;

    $fds = array('tbl', 'fld', 'event_details', 'event_date', 'event_by', 'status');
    $vals = array("$tbl", "$fld", "$event_details", "$fulldate", $event_by, 1);

    addtodb_v2('o_events', $fds, $vals);
}


// non-db functions
function get_vals_types($vals): string
{
    $types = "";
    foreach ($vals as $val) {
        if (is_int($val)) {
            $vals .= 'i'; // Integer
        } elseif (is_float($val)) {
            $vals .= 'd'; // Double/Float
        } else {
            $vals .= 's'; // String (default)
        }
    }
    return $types;
}

function isNotArrayOrObject($value): bool
{
    return !is_array($value) && !is_object($value);
}

function fetchtable2_v2($table, $category, $orderby, $dir, $fds = '*')
{
    global $pdo;  // Assuming $pdo is a PDO connection

    $query = "SELECT $fds FROM $table WHERE $category ORDER BY $orderby $dir";

    try {
        // Prepare the statement
        $stmt = $pdo->prepare($query);

        if (!$stmt) {
            throw new Exception("Error in preparing statement: " . $pdo->errorInfo()[2]);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->errorInfo()[2]);
        }

        // Fetch the result
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (Exception $e) {
        // Handle the exception
        echo "An error occurred: " . $e->getMessage();
        // You can choose to log the error or perform other error handling tasks here.
        return false; // Return false or a suitable default value to indicate failure.
    }
}
