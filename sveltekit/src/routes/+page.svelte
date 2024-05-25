<script>
   import Load from "$lib/components/Load.svelte";
   import PostForm from "$lib/components/PostForm.svelte";
   import Post from "$lib/components/Post.svelte";
   import CommentForm from "$lib/components/CommentForm.svelte";
   import Comment from "$lib/components/Comment.svelte";

   import { update } from "$lib/stores/update.js";
   import { user } from "$lib/stores/user.js";
   import { goto, afterNavigate } from "$app/navigation";
   import { auth } from "$lib/shared/auth.js";

   afterNavigate(async () => {
      $user = await auth();

      if (!$user.auth) {
         goto("/login");
      }
   });

   async function getAllPost() {
      let posts;
      const data = new FormData();
     const url = "/api/getallposts.php";

     const response = await fetch(url, {
        method: "post",
        body: data
       });
       posts = await response.json();
       return posts;
   }

 let postData = getAllPost();
$: if ($update) {
   $update = false;
   postData = getAllPost();
}

</script>

<section>
   <PostForm/>
   {#await postData}
      <Load/>
   {:then data} 
      {#each data as post }
         <Post post={post}/>
         <CommentForm commentID={post.pid} />
      {#each post.comments as comment}
         <Comment comment = {comment} />
      {/each}
      {/each}
   {/await} 
</section>


<style lang="scss">
   section {
      max-width: 540px;

      hr {
         margin-bottom: 16px;
         margin-top: 8px;
      }

      div {
         border-left: 2px solid green;
      }
   }
</style>
