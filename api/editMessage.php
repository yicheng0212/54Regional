<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (isset($_POST['delete']) && $_POST['delete'] == 1) {
        $sql = "UPDATE messages SET content = '此留言已被删除', deleted_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
    } else {
        $fieldsToUpdate = [
            'name' => $_POST['name'] ?? null,
            'email' => $_POST['email'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'content' => $_POST['content'] ?? null,
            'displayEmail' => isset($_POST['displayEmail']) ? (int)$_POST['displayEmail'] : null,
            'displayPhone' => isset($_POST['displayPhone']) ? (int)$_POST['displayPhone'] : null,
            'admin_response' => $_POST['admin_response'] ?? null,
            'image_path' => isset($_POST['image']) ? "./image/" . $_POST['image'] : null
        ];

        $sql = "UPDATE messages SET ";
        $setParts = [];
        $params = [];
        foreach ($fieldsToUpdate as $field => $value) {
            if ($value !== null) {
                $setParts[] = "$field = ?";
                $params[] = $value;
            }
        }
        $sql .= implode(', ', $setParts) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $params[] = $id;
    }


    $types = str_repeat("s", count($params) - 1) . "i";
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $response = isset($_POST['delete']) && $_POST['delete'] == 1 ? "留言已删除" : "留言已成功编辑";
        echo json_encode(['status' => 'success', 'message' => $response]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "操作失败：" . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
