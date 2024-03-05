<?php
include 'db.php';

header('Content-Type: application/json');

$id = $_POST['id'];

if (isset($_POST['delete']) && $_POST['delete'] == 1) {
    $sql = "UPDATE messages SET content = '此留言已被刪除', deleted_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
} else {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $content = $_POST['content'];
    $displayEmail = isset($_POST['displayEmail']) ? (int)$_POST['displayEmail'] : 0;
    $displayPhone = isset($_POST['displayPhone']) ? (int)$_POST['displayPhone'] : 0;
    $admin_response = $_POST['admin_response'];
    $image_path = isset($_POST['image']) ? "./image/" . $_POST['image'] : '';

    $sql = "UPDATE messages SET name = ?, email = ?, phone = ?, content = ?, displayEmail = ?, displayPhone = ?, admin_response = ?, image_path = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssi", $name, $email, $phone, $content, $displayEmail, $displayPhone, $admin_response, $image_path, $id);
}

if ($stmt->execute()) {
    $response = isset($_POST['delete']) && $_POST['delete'] == 1 ? "留言已删除" : "留言已成功编辑";
    echo json_encode(['status' => 'success', 'message' => $response]);
}
$stmt->close();
$conn->close();