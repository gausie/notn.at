/**
 * Keyboard navigation
 *
 * Yoink! https://gist.github.com/adamcapriola/3828418 
 *
 */
 
$(document).ready(function(){
 
    // This "clicks" the link
    $(function(){
        $('.previous-post').click(function(){
          location.href = $(this).attr('href');
        });
        $('.next-post').click(function() {
          location.href = $(this).attr('href');
        });
    });
 
    // This registers the keystroke
    $(document).keydown(function(event) {
        // Left arrow = previous link
        if(event.keyCode==37) {
            $('.previous-post').trigger('click');
        }
        // Right arrow = next link
        if(event.keyCode==39) {
            $('.next-post').trigger('click');
        }
    });
 
    // This prevents it from firing when typing in search box or comments
    $('input, textarea').keydown(function(event){
        event.stopPropagation();
    });
 
});
