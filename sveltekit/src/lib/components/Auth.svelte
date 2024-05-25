<script>
   import { user } from "$lib/stores/user.js";
   import { goto } from "$app/navigation";
   import { update } from "$lib/stores/update.js";

   async function signIn(e) {
      const data = new FormData(e.target);
     const url = "/api/auth.php";

     const response = await fetch(url, {
        method: "post",
        body: data
     });

     let check = await response.json();

     if(check.auth) {
      $user = check;
      $update = true;
      goto("/flow");
     }

  }

</script>

<form on:submit|preventDefault={signIn}>
   <label for="username">Användarnamn</label>
   <input id="username" type="text" name="username" />

   <label for="pwd">Lösenord</label>
   <input id="pwd" type="password" name="pwd" />

   <input type="submit" value="Logga in" />
</form>

<style lang="scss">
   label {
      display: block;
   }

   input {
      width: 100%;

      &[type="submit"] {
         color: white;
         background-color: #42b72a;
         border: none;
         margin: 10px 10px 0 0;
         padding: 0.7rem;
         border-radius: 6px;

         &:hover {
            background-color: green;
         }
      }
   }
</style>
