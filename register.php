<?php require("connect.php"); ?>

<script charset="utf-8" src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>

<form action="" method="POST">
    <label>Phone</label>
    <input id="customer" type="text" name="phone"/>
    <input id="lineId" type="hidden" name="lineId"/>
    <input type="submit" name="submit">
</form>


<?php
    if ( isset($_POST["submit"]) ) {
        $sql = "UPDATE customer SET customer_phone = '{$_POST["phone"]}' WHERE customer_name = '{$_POST["lineId"]}'";
        if ($result = $conn->query($sql)) {
            header("location:register.php");
        }
    }
?>

<script>

    liff.init({
        liffId: "1655276625-vBexNj4n" // Use own liffId
    }).then(() => {
        if (!liff.isLoggedIn()) { liff.login(); }
        const idToken = liff.getDecodedIDToken();
        document.getElementById("lineId").value = idToken.sub;
    }).catch((err) => {
        console.log(err.code, err.message);
    });

</script>