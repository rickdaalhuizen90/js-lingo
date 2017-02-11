<?=validation_errors(); ?>
<?=form_open('ValidateController'); ?>
    <input type="text" size="20" id="username" name="username" placeholder="Username">
    <input type="password" size="20" id="passowrd" name="password">
    <input type="submit" name="submit_login" value="Login"/>
</form>

<a href="register">Register</a>