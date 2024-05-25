<script>
   /**
    * Data som kommer in i komponenten
    * <Post post = {...} />
    */  
    export let post;
</script>

<article>
<b>{post.firstname} {post.surname} {post.date} </b>
<p> {post.post_txt} </p>
</article>

<style lang="scss">
   article {
      border-bottom: 1px solid black;
      display: block;
      width: 100%;
      padding: 4px;
      margin-top: 6px;
      margin-bottom: 6px;
      background-color: white;

      p {
         font-size: 1.6rem;
      }
   }
</style>
