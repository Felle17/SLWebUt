<script>
   /**
    * Omdirigerar! Kan tas bort helt.
    * Kan användas vid alternativ lösning.
    */
   import { user } from "$lib/stores/user.js";
   import { goto, afterNavigate } from "$app/navigation";

   afterNavigate(async () => {
      $user = await auth();

      if (!$user.auth) goto("/");
      else goto("/flow/{$user.userdata.uid}");

   });
</script>
