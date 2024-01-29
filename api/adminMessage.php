<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $messageId = $_POST['message_id'];

    switch ($action) {
        case 'delete':
            // 刪除留言
            $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
            $stmt->bind_param("i", $messageId);
            break;

        case 'edit':
            // 編輯留言
            $displayPhone = $_POST['display_phone'];
            $displayEmail = $_POST['display_email'];
            $adminResponse = $_POST['admin_response'];

            $stmt = $conn->prepare("UPDATE messages SET display_phone = ?, display_email = ?, admin_response = ? WHERE id = ?");
            $stmt->bind_param("iisi", $displayPhone, $displayEmail, $adminResponse, $messageId);
            break;

        case 'toggleTop':
            // 置頂/取消置頂留言
            $isTop = $_POST['is_top']; // 這裡應該是0或1

            $stmt = $conn->prepare("UPDATE messages SET is_top = ? WHERE id = ?");
            $stmt->bind_param("ii", $isTop, $messageId);
            break;

        default:
            echo "無效的操作";
            exit;
    }

    // 執行預備語句
    if ($stmt->execute()) {
        echo "操作成功";
    } else {
        echo "錯誤: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();