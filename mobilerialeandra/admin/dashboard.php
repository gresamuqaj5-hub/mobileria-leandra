<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

// DB Connection
$conn = new mysqli('localhost', 'root', '', 'mobilerialeandra');
$conn->set_charset('utf8mb4');

// Fshi mesazhin
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM messages WHERE id = $id");
    header('Location: dashboard.php?deleted=1');
    exit;
}

// Shëno si lexuar
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $conn->query("UPDATE messages SET is_read = 1 WHERE id = $id");
    header('Location: dashboard.php');
    exit;
}

// Merr të gjitha mesazhet
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $result->fetch_all(MYSQLI_ASSOC);

// Numri i mesazheve të palexuara
$unread = $conn->query("SELECT COUNT(*) as cnt FROM messages")->fetch_assoc()['cnt'];
//$unread = $conn->query("SELECT COUNT(*) as cnt FROM messages WHERE is_read = 0")->fetch_assoc()['cnt'];//

$conn->close();
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard — Mobileria Leandra</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Jost', sans-serif;
      background: #1a1007;
      color: #f5ede0;
      min-height: 100vh;
    }

    /* HEADER */
    .admin-header {
      background: rgba(20,10,4,0.97);
      border-bottom: 1px solid rgba(255,255,255,0.06);
      padding: 0 40px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .admin-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem;
      color: #c9963e;
    }
    .admin-nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .badge {
      background: #c9963e;
      color: #1a1007;
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 8px;
      border-radius: 50px;
    }
    .logout-btn {
      padding: 8px 20px;
      border: 1px solid rgba(201,150,62,0.4);
      color: #c9963e;
      border-radius: 50px;
      font-size: 0.82rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s;
    }
    .logout-btn:hover {
      background: #c9963e;
      color: #1a1007;
    }

    /* MAIN */
    .main {
      max-width: 1100px;
      margin: 0 auto;
      padding: 48px 24px;
    }

    .page-title {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: #fff;
      margin-bottom: 8px;
    }
    .page-sub {
      font-size: 0.88rem;
      color: #8a7060;
      margin-bottom: 36px;
    }

    /* STATS */
    .stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 40px;
    }
    .stat-card {
      background: rgba(255,255,255,0.04);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 14px;
      padding: 24px 28px;
    }
    .stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 2.4rem;
      color: #c9963e;
      line-height: 1;
      margin-bottom: 6px;
    }
    .stat-label {
      font-size: 0.8rem;
      color: #8a7060;
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }

    /* ALERT */
    .alert {
      background: rgba(80,170,80,0.12);
      border: 1px solid rgba(80,170,80,0.3);
      border-radius: 8px;
      color: #8dce8d;
      padding: 12px 18px;
      font-size: 0.88rem;
      margin-bottom: 24px;
    }

    /* TABLE */
    .table-wrap {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.07);
      border-radius: 16px;
      overflow: hidden;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    thead {
      background: rgba(201,150,62,0.1);
    }
    th {
      padding: 14px 20px;
      text-align: left;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #c9963e;
    }
    td {
      padding: 16px 20px;
      font-size: 0.9rem;
      color: #d4bfa8;
      border-top: 1px solid rgba(255,255,255,0.05);
      vertical-align: top;
    }
    tr.unread td {
      background: rgba(201,150,62,0.05);
    }
    tr.unread td:first-child {
      border-left: 3px solid #c9963e;
    }
    .msg-text {
      max-width: 280px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: #8a7060;
      font-size: 0.85rem;
    }
    .date {
      font-size: 0.8rem;
      color: #8a7060;
      white-space: nowrap;
    }
    .actions {
      display: flex;
      gap: 8px;
      white-space: nowrap;
    }
    .btn-read {
      padding: 6px 14px;
      background: rgba(201,150,62,0.15);
      border: 1px solid rgba(201,150,62,0.3);
      color: #c9963e;
      border-radius: 50px;
      font-size: 0.78rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s;
    }
    .btn-read:hover {
      background: #c9963e;
      color: #1a1007;
    }
    .btn-delete {
      padding: 6px 14px;
      background: rgba(200,60,60,0.1);
      border: 1px solid rgba(200,60,60,0.25);
      color: #e08080;
      border-radius: 50px;
      font-size: 0.78rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s;
    }
    .btn-delete:hover {
      background: rgba(200,60,60,0.3);
    }
    .empty {
      text-align: center;
      padding: 60px;
      color: #8a7060;
      font-size: 0.95rem;
    }
    .new-badge {
      display: inline-block;
      background: #c9963e;
      color: #1a1007;
      font-size: 0.65rem;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 50px;
      margin-left: 6px;
      vertical-align: middle;
      letter-spacing: 0.05em;
    }
  </style>
</head>
<body>

  <header class="admin-header">
    <div class="admin-logo">Mobileria Leandra</div>
    <div class="admin-nav">
      <?php if ($unread > 0): ?>
        <span class="badge"><?= $unread ?> të reja</span>
      <?php endif; ?>
      <a href="logout.php" class="logout-btn">Dil</a>
    </div>
  </header>

  <div class="main">
    <h1 class="page-title">Panel Administrativ</h1>
    <p class="page-sub">Të gjitha mesazhet nga forma e kontaktit</p>

    <?php if (isset($_GET['deleted'])): ?>
      <div class="alert">✓ Mesazhi u fshi me sukses.</div>
    <?php endif; ?>

    <!-- STATS -->
    <div class="stats">
      <div class="stat-card">
        <div class="stat-num"><?= count($messages) ?></div>
        <div class="stat-label">Mesazhe gjithsej</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?= $unread ?></div>
        <div class="stat-label">Të palexuara</div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?= count($messages) - $unread ?></div>
        <div class="stat-label">Të lexuara</div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrap">
      <?php if (empty($messages)): ?>
        <div class="empty">Nuk ka mesazhe ende.</div>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Emri</th>
              <th>Email</th>
              <th>Projekti</th>
              <th>Mesazhi</th>
              <th>Data</th>
              <th>Veprime</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($messages as $msg): ?>
              <tr class="<?= $msg['is_read'] ? '' : 'unread' ?>">
                <td><?= $msg['id'] ?></td>
                <td>
                  <?= htmlspecialchars($msg['name']) ?>
                  <?php if (!$msg['is_read']): ?>
                    <span class="new-badge">RE</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= htmlspecialchars($msg['project']) ?></td>
                <td>
                  <div class="msg-text" title="<?= htmlspecialchars($msg['message']) ?>">
                    <?= htmlspecialchars($msg['message']) ?>
                  </div>
                </td>
                <td class="date"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></td>
                <td>
                  <div class="actions">
                    <?php if (!$msg['is_read']): ?>
                      <a href="?read=<?= $msg['id'] ?>" class="btn-read">✓ Lexuar</a>
                    <?php endif; ?>
                    <a href="?delete=<?= $msg['id'] ?>" class="btn-delete" onclick="return confirm('A jeni i sigurt?')">Fshi</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>