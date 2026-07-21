<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation - {{ $event->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --brilliant-green: #7ca361;
            --brilliant-green-dark: #6b8e52;
            --brilliant-green-light: #d5e6d5;
            --brilliant-bg: #f8faf8;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            background-color: var(--brilliant-bg);
            background-image:
                radial-gradient(at 0% 0%, rgba(124, 163, 97, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(124, 163, 97, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #2d3748;
            padding: 20px 0 40px;
        }

        .container {
            max-width: 800px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--brilliant-green-dark);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            transition: all 0.3s ease;
            background: var(--glass-bg);
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .back-btn:hover {
            transform: translateX(-5px);
            color: var(--brilliant-green);
        }

        .doc-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .doc-header h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: #1a202c;
            margin-bottom: 10px;
        }

        .doc-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .drive-icon {
            font-size: 4rem;
            color: var(--brilliant-green);
            margin-bottom: 20px;
        }

        .doc-link-btn {
            background: var(--brilliant-green);
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 25px rgba(124, 163, 97, 0.3);
            margin-top: 20px;
        }

        .doc-link-btn:hover {
            background: var(--brilliant-green-dark);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(124, 163, 97, 0.4);
        }

        .empty-state {
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        /* Iframe preview container */
        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 75%;
            /* 4:3 Aspect Ratio */
            height: 0;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            margin-top: 30px;
            background: #eee;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>

<body>
    @php
        $p = $event->personalization ?? [];
        $driveLink = $p['documentation_link'] ?? null;

        // Extract Folder ID if possible for embedded view
        $folderId = null;
        if ($driveLink && preg_match('/folders\/([a-zA-Z0-9-_]+)/', $driveLink, $matches)) {
            $folderId = $matches[1];
        }
    @endphp

    <div class="container">
        <a href="{{ route('qr.client.redirect', $token) }}" class="back-btn">
            <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
        </a>

        <div class="doc-header">
            <h1>Documentation</h1>
            <p class="text-muted">Capture the beautiful moments of our wedding</p>
        </div>

        <div class="doc-card">
            @if($driveLink)
                <div class="drive-icon">
                    <i class="bi bi-camera-fill"></i> <!-- Google Drive stylized icon -->
                </div>
                <h3>Official Wedding Gallery</h3>
                <p class="text-muted mb-4">Click the button below to view all photos and videos shared in our Google Drive
                    folder.</p>

                <a href="{{ $driveLink }}" target="_blank" class="doc-link-btn">
                    <i class="bi bi-folder2-open"></i> Open Google Drive
                </a>

                @if($folderId)
                    <div class="mt-5">
                        <h5 class="fw-bold mb-3">Gallery Preview</h5>
                        <div class="iframe-container">
                            <iframe src="https://drive.google.com/embeddedfolderview?id={{ $folderId }}#grid"
                                allowfullscreen></iframe>
                        </div>
                        <div class="mt-2 x-small text-muted">
                            <i class="bi bi-info-circle me-1"></i> If the preview doesn't load, please use the button above.
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="bi bi-camera-reels"></i>
                    <h3>No Documentation Yet</h3>
                    <p>The documentation for this event hasn't been linked yet. Please check back later!</p>
                </div>
            @endif
        </div>

        <div class="text-center mt-5">
            <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Logo" style="height: 30px; opacity: 0.5;">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>