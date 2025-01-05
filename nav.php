<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#" > <img src="fhtw-logo.svg" width="30" height="30" alt=""></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>

       
          <li class="nav-item">
            <a class="nav-link" href="news.php">News</a>
          </li>
       
        <?php if (is_session_active()): ?>
          <li class="nav-item">
            <a class="nav-link" href="profil.php">Profil anzeigen</a>
          </li>
        <?php endif; ?>

        <?php if (is_session_active() && isset($rRole) && $rRole == 'ADMIN'): ?>
          <li class="nav-item">
            <a class="nav-link" href="userlist.php">Userliste</a>
          </li>
        <?php endif; ?>

        <?php if (!is_session_active()): ?>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Registrieren</a>
          </li>
        <?php endif; ?>


        <?php if (is_session_active()): ?>
          <li class="nav-item">
            <a class="nav-link" href="reservation.php">Reservierung vornehmen</a>
          </li>
        <?php endif; ?>

        <?php if (is_session_active()): ?>
          <li class="nav-item">
            <a class="nav-link" href="reservationlist.php">Reservierungen anzeigen</a>
          </li>
        <?php endif; ?>

         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Hilfe & Rechtliches
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="help.php">Hilfe</a></li>

            <?php if (is_session_active() && isset($rRole) && $rRole == 'ADMIN'): ?>
            <li><a class="dropdown-item" href="crnews.php">Newsbeitrag</a></li>
            <?php endif; ?>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="impressum.php">Impressum</a></li> 
          </ul>
        </li>

      </ul>
      <?php if (is_session_active()): ?>
        <a href="logout.php" class="btn btn-primary ms-auto">Logout </a>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary ms-auto">Login</a>
      <?php endif; ?>
    </div>

  </div>
</nav>