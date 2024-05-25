<script>
   import Signup from "$lib/components/Signup.svelte";
   import { goto } from '$app/navigation';
</script>

<aside>
   <img src="/images/mobile.png" alt="Mobiltelefon" width="240" />
</aside>
<section>
   <h1>Skapa konto på EGY Talk</h1>
   <Signup />
   <p>eller</p>
   <button on:click={() => goto('/login')}>Logga In</button>
</section>

<style lang="scss">
   aside,
   section {
      display: inline-block;
      vertical-align: top;
   }

   aside {
      margin-right: 10px;
   }

   p {
      width: fit-content;
      margin-left: auto;
      margin-right: auto;
      margin-top: 1rem;
   }

   button {
      width: 100%;
      color: white;
      background-color: grey;
      border: none;
      margin: 10px 10px 0 0;
      padding: 0.7rem;
      border-radius: 6px;

      &:hover {
         background-color: #aeaeae;
      }
   }

   @media only screen and (max-width: 675px) {
      img {
         display: none;
      }
   }
</style>
