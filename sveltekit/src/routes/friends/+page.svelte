<script>
   import Load from "$lib/components/Load.svelte";
   import { user } from "$lib/stores/user.js";
   import { goto, afterNavigate } from "$app/navigation";
   import { auth } from "$lib/shared/auth.js";

   afterNavigate(async () => {
      $user = await auth();

      if (!$user.auth) {
         goto("/login");
      }
   });
   
   async function getusers() {
      let user;
      const data = new FormData();
     const url = "/api/getusers.php";

     const response = await fetch(url, {
       });
       user = await response.json();
       return user;
   }


</script>

<h1>Vänner</h1>
<section>
   {#await getusers()}
<Load /> <!-- mest för att visa hur den fungerar -->
{:then data} 
   {#each data as user}
   <a href="/flow/{user.uid}">{user.firstname} {user.surname}</a>
      <br>
   {/each}
   {/await}
</section>

<style lang="scss">
   section {
      max-width: 540px;
   }
</style>
