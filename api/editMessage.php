<?php
include 'db.php';

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
            'display_email' => isset($_POST['display_email']) ? 1 : 0,
            'display_phone' => isset($_POST['display_phone']) ? 1 : 0
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
        echo isset($_POST['delete']) && $_POST['delete'] == 1 ? "留言已删除" : "留言已成功编辑";
    } else {
        echo "操作失败：" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>