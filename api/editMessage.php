<?php
include 'db.php';

header('Content-Type:application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (isset($_POST['delete']) && $_POST['delete'] == 1) {
        $stmt = $conn->prepare("UPDATE messages SET content = '此留言已被删除', deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        $fieldsToUpdate = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'content' => $_POST['content'] ?? '',
            'displayEmail' => isset($_POST['displayEmail']) ? (int)$_POST['displayEmail'] : 0,
            'displayPhone' => isset($_POST['displayPhone']) ? (int)$_POST['displayPhone'] : 0
        ];
        if (!empty($_POST['admin_response'])) {
            $fieldsToUpdate['admin_response'] = $_POST['admin_response'];
        }
        $setParts = [];
        foreach ($fieldsToUpdate as $field => $value) {
            $setParts[] = "$field = ?";
        }
        $sql = "UPDATE messages SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $fieldsToUpdate['id'] = $id;
        $stmt->execute(array_values($fieldsToUpdate));
    }

    if ($stmt) {
        $response = isset($_POST['delete']) && $_POST['delete'] == 1 ? "留言已删除" : "留言已成功编辑";
        echo json_encode(['status' => 'success', 'message' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "操作失败：" . $conn->error]);
    }

    $stmt->close();
}

$conn->close();