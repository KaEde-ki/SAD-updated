<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>SyncEase • Welcome</title>
    <style>
    /* Base Reset & Body */
    *, *::before, *::after {
        box-sizing: border-box;
    }
    html {
        scroll-behavior: smooth;
    }
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.5;
        background: #f9f9f9;
    }

    /* Hero Banner */
    header {
        background: linear-gradient(45deg, #4e4a81, #6c63ff);
        color: #fff;
        padding: 2rem 1rem;
        text-align: left;
        animation: fadeIn 1s ease-out both;
    }
    header h1 {
        margin: 0;
        font-size: 2rem;
    }
    header h2 {
        margin: 0.25rem 0 0;
        font-size: 1rem;
        font-weight: normal;
        opacity: 0.9;
    }

    /* Main Content */
    main {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 4rem 1rem;
    }
    main h1 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }
    main p {
        margin-bottom: 2rem;
        color: #555;
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        background: #6c63ff;
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        margin: 0.5rem;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 4px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;

        transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }
    .btn:hover,
    .btn:focus {
        transform: translateY(-2px);
        background: #5852d0;
        outline: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn:focus-visible {
        outline: 3px dashed #ffb400;
        outline-offset: 3px;
    }

    /* Responsive Layout */
    @media (min-width: 600px) {
        main {
        max-width: 400px;
        margin: 0 auto;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseBG {
        0%, 100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }
</style>
</head>
<body>

    <header role="banner">
        <h1 id="site-title">SyncEase</h1>
        <h2>Your productivity tracker</h2>
    </header>

<main role="main" aria-labelledby="welcome-heading">
    <h1 id="welcome-heading">Welcome!</h1>
    <p>Please choose an option to get started:</p>
    <nav role="navigation" aria-label="Authentication options">
        <a href="login.php" class="btn" role="button" aria-describedby="login-desc">
        Login
        </a>
        <a href="signin.php" class="btn" role="button" aria-describedby="signup-desc">
        Sign Up
        </a>
    </nav>
</main>

</body>
</html>
