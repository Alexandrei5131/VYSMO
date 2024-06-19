<?php
    session_start();

    function pathTo($pageName) {
        echo "<script>window.location.href = 'pages/$pageName.php'</script>";
    }
    
    if(isset($_SESSION['statpage'])){
        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set status to invalid */
            $_SESSION['statpage'] = 'invalid';
    
            unset($_SESSION['accountID']);
            unset($_SESSION['email']);
            unset($_SESSION['role']);
            unset($_SESSION['accountQR']);
            
            echo "<script>window.location.href = '../'</script>";
    
        }
        else{//meaning valid
            //check yung everypage after the include session.php
        }
    }
    else{
        echo "<script>window.location.href = '../'</script>";
    }
    
    
?>