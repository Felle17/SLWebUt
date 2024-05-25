<script>
   import { update } from "$lib/stores/update.js";

   export let commentID;

   async function addComment(e) {
      const data = new FormData(e.target);
      data.append('pid', commentID);
      console.log(data);
      const url = "/api/addcomment.php";
      $update = true;

      const response = await fetch(url, {
         method: "post",
         body: data,
      });
   }
</script>

<form on:submit|preventDefault={addComment}>
   <input type="text" name="comments_txt" placeholder="Kommentera något!">
   <input type="submit">
</form>

<style lang="scss">
   form {
      display: flex;
      flex-direction: row;

      width: 100%;
      input[type="text"] {
         width: 100%;
      }
      
      input[type="submit"] {
         border: none;
         border-radius: 4px;
         padding: 5px 15px;
         text-decoration: none;
         color: white;
         background-color: #42b72a;
         margin-left: 2px;
         &:hover {
            background-color: darkgreen;
            color: #ffffff;
         }
      }
   }
</style>
