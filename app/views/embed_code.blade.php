<div class="panel panel-warning">
    <div class="panel-heading"><b>Embed Code</b></div>
    <div class="panel-body">
        <textarea id="embed-textarea" style="width: 100%;  height: 40px; resize: none; font-family: sans-serif; font-size: 15px;"><script src="{{ \App\Models\Code::$VIEW_LINK }}embed.js?c={{ $_id }}" type="text/javascript"></script></textarea>
    </div>
 </div>
<br>
<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 'phpboxx';
    
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>