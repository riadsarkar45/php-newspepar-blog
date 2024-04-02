<?php
include("include/session.php");
include("include/db.php");
$userId = $_SESSION["User_Id"];

$html = '';
$time = time() + 10;
$limit = 5;
$page = isset($_GET['user_page']) ? $_GET['user_page'] : 1;
$offset = ($page - 1) * $limit;

$total_rows_stmt = $connect->prepare('SELECT COUNT(*) AS total FROM users');
$total_rows_stmt->execute();
$total_rows = $total_rows_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_rows / $limit);

$count_cat = $connect->query("SELECT COUNT(*) FROM users WHERE id != '$userId' ")->fetchColumn();
$totalRecords = $count_cat;

$stmt = $connect->prepare("SELECT * FROM users WHERE id != '$userId' LIMIT :offset, :limit");
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();


foreach ($users as $rows) {
    $status = 'Pending';
    $class = 'warning';
    if ($rows['status'] === 'ON') {
        $status = 'Approved';
        $class = 'success';
    }

    if ($rows['role'] == '1') {
        $role = "Administrator";
    }
    if ($rows['role'] == '2') {
        $role = "Editor";
    }
    if ($rows['role'] == '3') {
        $role = "Author";
    }
    if ($rows['role'] == '4') {
        $role = "Subscriber";
    }
    if ($rows['role'] == '5') {
        $role = "Visitor";
    }

    $time = time();

    $isOnline = 'danger';
    $text = 'Offline';
    if ($rows['last_Logn'] > $time) {
        $isOnline = 'success';
        $text = 'Online';
    }

    $html .= '
    
    <tr>
    <th>
    <label>
        <input type="checkbox" class="checkbox" />
    </label>
    </th>
    <td>
        <div>
        <div class="font-bold">' . $rows['username'] . '</div>
        </div>
    </td>
    <td>
    <span class="badge badge-ghost badge-sm">' . $role . '</span>
    </td>
    <td>
        <span class="label label-' . $class . '">' . $status . '</span>
    </td>
    <th>
    <span class="label label-' . $isOnline . '">' . $text . '</span>
    </th>
</tr>
   ';
}
echo $html;
