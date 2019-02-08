<?php
$messages = $this->messages->get() ?: '';
if (is_array($messages))
{
        if (count($messages['success']) > 0)
        {
                echo "<div class='alert alert-success' role='alert'>";
                echo "<h4><i class='fa fa-check'></i> SUCCESS!</h4>";
//                echo "<ul>";
                foreach ($messages['success'] as $message) {
                        echo ($message);
                }
//                echo "</ul>";
                echo "</div>";
        }
        if (count($messages['error']) > 0)
        {
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<h4><i class='fa fa-ban'></i> ERROR!</h4>";
                echo "<ul>";
                foreach ($messages['error'] as $message) {
                        if (substr($message, 0, 4) == "<li>")
                                echo ($message);
                        else
                                echo ('<li>' . $message . '</li>');
                }
                echo "</ul>";
                echo "</div>";
        }
        if (count($messages['message']) > 0)
        {
                echo "<div class='alert alert-info' role='alert'>";
                echo "<h4><i class='fa fa-info'></i> INFO!</h4>";
                echo "<ul>";
                foreach ($messages['message'] as $message) {
                        echo ('<li>' . $message . '</li>');
                }
                echo "</ul>";
                echo "</div>";
        }
        if (count($messages['warning']) > 0)
        {
                echo "<div class='alert alert-warning' role='alert'>";
                echo "<h4><i class='fa fa-warning'></i> WARNING!</h4>";
                echo "<ul>";
                foreach ($messages['warning'] as $message) {
                        echo ('<li>'. $message . '</li>');
                }
                echo "</ul>";
                echo "</div>";
        }
}
?>