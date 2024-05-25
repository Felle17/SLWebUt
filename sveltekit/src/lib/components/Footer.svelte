<script>
   import { user } from "$lib/stores/user.js";
</script>

<footer>
   <p>
      EGY TALK
      {#if $user.auth}
         Användare:
         {$user.userdata.firstname + " " + $user.userdata.surname}
      {/if}
   </p>
</footer>

<style lang="scss">
   footer {
      height: 45px;
      width: 100%;
      background-image: url("/images/bgImgLarge.png");
      border-top: 2px solid rgb(124, 10, 10);
      position: absolute;
      bottom: 0;

      p {
         color: white;
         padding: 1rem;
      }
   }
</style>
