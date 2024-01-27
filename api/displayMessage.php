<?php
include 'db.php';

$sql = "SELECT id, name, message_number, email, phone, content, image_path, created_at, updated_at, deleted_at, display_email, display_phone FROM messages ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        if ($row["deleted_at"] == NULL) {
            // 正常顯示留言
            echo "<h5 class='card-title'>" . htmlspecialchars($row["name"]) . "</h5>";
            echo "<h6 class='card-subtitle mb-2 text-muted'>留言編號: " . htmlspecialchars($row["message_number"]) . "</h6>";
            echo "<p class='card-text'>" . nl2br(htmlspecialchars($row["content"])) . "</p>";

            // 顯示圖片
            if (!empty($row["image_path"])) {
                echo "<img src='" . htmlspecialchars($row["image_path"]) . "' class='card-img-top' style='max-width: 300px; height: auto;' alt='圖片'>";
            }

            // 顯示電子郵件和電話
            if ($row["display_email"]) {
                echo "<p class='card-text'>Email: " . htmlspecialchars($row["email"]) . "</p>";
            }
            if ($row["display_phone"]) {
                echo "<p class='card-text'>電話: " . htmlspecialchars($row["phone"]) . "</p>";
            }

            // 其他信息
            echo "<p class='card-text'><small class='text-muted'>發表於 " . $row["created_at"];
            if ($row["updated_at"] != $row["created_at"]) {
                echo "，修改於 " . $row["updated_at"];
            }
            echo "</small></p>";

            // 編輯和刪除按鈕
            echo "<button onclick='verifyAndEditMessage(" . $row["id"] . ")' class='btn btn-primary'>编辑</button> ";
            echo "<button onclick='verifyAndDeleteMessage(" . $row["id"] . ")' class='btn btn-danger'>删除</button>";
        } else {
            // 顯示已刪除的留言
            echo "<h5 class='card-title'>" . htmlspecialchars($row["name"]) . "</h5>";
            echo "<p class='card-text'>此留言已被刪除。</p>";
            echo "<p class='card-text'><small class='text-muted'>發表於 " . $row["created_at"] . "，刪除於 " . $row["deleted_at"] . "</small></p>";
        }
        echo "</div>";
        echo "</div><br>";
    }
} else {
    echo "沒有找到留言";
}

$conn->close();
