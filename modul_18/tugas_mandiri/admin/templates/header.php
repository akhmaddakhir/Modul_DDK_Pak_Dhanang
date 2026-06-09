<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel Modul 18</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      display: flex;
      min-height: 100vh;
      background: #f4f7fb;
      color: #172033;
    }

    .sidebar {
      width: 240px;
      background: #17324d;
      color: white;
      min-height: 100vh;
      padding: 22px 16px;
      position: sticky;
      top: 0;
    }

    .sidebar h2 {
      font-size: 22px;
      margin-bottom: 24px;
    }

    .sidebar a {
      display: block;
      padding: 12px 14px;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 8px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #1abc9c;
      color: #082f2a;
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .topbar {
      background: white;
      border-bottom: 1px solid #e5e7eb;
      padding: 14px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .topbar span {
      color: #64748b;
      margin-left: 8px;
      text-transform: capitalize;
    }

    .content {
      flex: 1;
      padding: 24px;
    }

    .footer {
      background: #e9eef5;
      padding: 12px;
      text-align: center;
      color: #526071;
    }

    .thumb {
      width: 92px;
      height: 64px;
      object-fit: cover;
      border-radius: 8px;
    }

    .hero-img {
      width: 100%;
      max-height: 360px;
      object-fit: cover;
      border-radius: 12px;
    }

    @media (max-width: 768px) {
      body {
        display: block;
      }

      .sidebar {
        width: 100%;
        min-height: auto;
        position: static;
      }

      .content {
        padding: 16px;
      }
    }
  </style>
</head>

<body>