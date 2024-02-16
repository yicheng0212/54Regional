<?php
include "db.php";

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? 0;
switch ($action) {
    case 'delete':
        echo json_encode(["message" => deleteMessage($id)]);
        break;
    case 'edit':
        $content = $_POST['content'] ?? '';
        $displayEmail = $_POST['display_email'] ?? 0;
        $displayPhone = $_POST['display_phone'] ?? 0;
        echo json_encode(["message" => editMessage($id, $content, $displayEmail, $displayPhone)]);
        break;
    case 'top':
        echo json_encode(["message" => topMessage($id)]);
        break;
    case 'respond':
        $adminResponse = $_POST['admin_response'] ?? '';
        echo json_encode(["message" => respondToMessage($id, $adminResponse)]);
        break;
    default:
        echo json_encode(["error" => "無效的操作"]);
}

// 留言操作函數
function deleteMessage($id) {
    global $conn;
    $sql = "UPDATE messages SET deleted_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? "留言已刪除。" : "Error: " . $conn->error;
}

function editMessage($id, $content, $displayEmail, $displayPhone) {
    global $conn;
    $sql = "UPDATE messages SET content = ?, display_email = ?, display_phone = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siii", $content, $displayEmail, $displayPhone, $id);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? "Message updated successfully." : "Error: " . $conn->error;
}

function topMessage($id) {
    global $conn;
    $sql = "UPDATE messages SET is_top = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? "Message topped successfully." : "Error: " . $conn->error;
}

function respondToMessage($id, $adminResponse)
{
    global $conn;
    $sql = "UPDATE messages SET admin_response = ?, updated_at = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $adminResponse, $id);
    $stmt->execute();
    return $stmt->affected_rows > 0 ? "回應已更新。" : "Error: " . $conn->error;
}