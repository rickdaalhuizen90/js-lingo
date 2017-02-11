<?=validation_errors(); ?>
<?=form_open('ValidateController'); ?>
    <input type="text" size="20" id="username" name="username" placeholder="Username">
    <input type="email" size="20" id="email" name="email" placeholder="Email">
    <input type="password" size="20" id="passowrd" name="password">
    <input type="password" size="20" id="cpassowrd" name="password">
    <input type="submit" name="submit_register" value="Register">
</form>
<a href="login">Login</a>