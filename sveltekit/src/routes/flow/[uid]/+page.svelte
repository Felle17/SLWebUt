<script>
   import { page } from "$app/stores";
   import Load from "$lib/components/Load.svelte";
   import PostForm from "$lib/components/PostForm.svelte";
   import Post from "$lib/components/Post.svelte";
   import CommentForm from "$lib/components/CommentForm.svelte";
   import Comment from "$lib/components/Comment.svelte";

   import { user } from "$lib/stores/user.js";
   import { update } from "$lib/stores/update.js";
   import { goto, afterNavigate } from "$app/navigation";
   import { auth } from "$lib/shared/auth.js";
   let uid;

   $: uid = $page.params.uid;

   afterNavigate(async () => {
      $user = await auth();
      uid = $user.userdata.uid;
      if (!$user.auth) {
         goto("/login");
      }
   });

   async function getPost() {
      let post;
      const data = new FormData();
      data.append("uid", $page.params.uid);
      const url = "/api/getposts.php";

      const response = await fetch(url, {
         method: "post",
         body: data,
      });
      post = await response.json();
      return post;
   }
   async function getUser() {
      const url = `/api/getuser.php?uid=${uid}`;
      user = await response.json();
      return user;
   }
   let name = getUser();

   let postData = getPost();
   $: if ($update) {
      $update = false;
      postData = getPost();
   }
</script>

<h1>{name} TALK</h1>

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
