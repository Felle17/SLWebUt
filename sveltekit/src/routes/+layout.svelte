<script>
   import "$lib/global.scss";
   import Header from "$lib/components/Header.svelte";
   import Footer from "$lib/components/Footer.svelte";
</script>

<head:title>
   <title>EGY Talk</title>
</head:title>

<Header />
<main>
   <slot />
</main>
<Footer />
