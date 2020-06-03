<html>
    <head>
        <script
            src="https://secure.safesavegateway.com/token/Collect.js"
            data-tokenization-key="v6836S-ZUA6Z3-Cy787A-Y8MZSP"
            data-payment-selector=".customPayButton"
            data-theme="bootstrap"
            data-primary-color="#ff288d"
            data-secondary-color="#ffe200"
            data-button-text="SUBMIT ME!"
            data-payment-type="cc"
            data-field-cvv-display="hide"
            data-instruction-text="Enter Card Information"
        ></script>
    </head>
    <body>
        <h1>CollectJS Payment Form</h1>
        <form action="/society_checkout/print.php" method="post">
            <table>
                <tr>
                    <td>First Name</td>
                    <td><input size="30" type="text" name="fname" value="Test" /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input size="30" type="text" name="lname" value="User" /></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><input size="30" type="text" name="address" value="123 Main Street"></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td><input size="30" type="text" name="city" value="Beverley Hills"></td>
                </tr>
                <tr>
                    <td>State</td>
                    <td><input size="30" type="text" name="state" value="CA"></td>
                </tr>
                <tr>
                    <td>Zip</td>
                    <td><input size="30" type="text" name="zip" value="90210"></td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td><input size="30" type="text" name="country" value="US"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input size="30" type="text" name="phone" value="5555555555"></td>
                </tr>
            </table>
            <br>
            <button class="customPayButton" type="button">Pay the money.</button>
        </form>
    </body>
</html>