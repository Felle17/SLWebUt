<script>
   import { onMount } from 'svelte';
   let settings = {"phone": "", "mail": ""};

   async function changeSettings(e){
      const data = new FormData(e.target);
     const url = "/api/updatesettings.php";

     const response = await fetch(url, {
        method: "post",
        body: data
     });

     let check = await response.json();
   }

   onMount(() => {
      // Här kan du skriva kod som ska köras när komponenten har monterats
      console.log("Komponenten har monterats!");

      // Exempel på att ladda initiala data
      // Här skulle du kunna göra en fetch för att hämta tidigare sparade inställningar
      fetch('/api/getsettings.php')
         .then(response => response.json())
         .then(data => {
            settings.phone = data.phone;
            settings.mail = data.mail;
         });
   });
</script>

<form on:submit|preventDefault={changeSettings}>
   <fieldset>
      <legend>Kontaktuppgifter</legend>
      <p>Telefon</p>
      <input type="text" name="phone" placeholder="Skriv ditt telefonnummer här!">
      <p>Email</p>
      <input type="text" name="mail" placeholder="Skriv din email här!">
      <input type="submit" value="Spara">
   </fieldset>
</form>

<style lang="scss">
   fieldset {
      padding: 0.5rem;
      legend {
         padding: 0.5rem;  
      }
   }

   label {
      margin-top: 0.5rem;
      display: block;
   }

   input {
      display: inline-block;
      width: 90%;
      &[type="submit"] {
         color: white;
         background-color: #42b72a;
         border: none;
         margin: 10px 10px 0 0;
         padding: 0.5rem 1rem;
         border-radius: 6px;
         display: inline;
         width: auto;
         &:hover {
            background-color: green;
         }
      }
   }
</style>
