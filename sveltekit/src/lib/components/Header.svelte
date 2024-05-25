<script>
    import Nav from "$lib/components/Nav.svelte";
    import { user } from "$lib/stores/user.js";
</script>

<header>
    <img src="/images/loggoegy.png" alt="loggo" id="logga" />
    <h1>EGY TALK</h1>

    {#if $user.auth}
        <Nav />
    {/if}
</header>

<style lang="scss">
    header {
        width: 100%;
        font-size: 1.2rem;
        text-align: left;
        height: 80px;
        padding-top: 5px;
        background-image: url("/images/bgImg.png");
        background-repeat: repeat;
        color: white;
        position: relative;
        border-bottom: 2px solid rgb(124, 10, 10);

        h1 {
            display: inline-block;
            font-family: saker, Arial;
            letter-spacing: 3px;
            font-size: 2.6rem;
            margin-top: 1.8rem;
            margin-left: 2.5rem;
        }

        img {
            float: left;
            height: 50px;
            margin-top: 10px;
            margin-left: 50px;
        }
    }

    @media only screen and (max-width: 1024px) {
        header {          
            height: 12rem;

            img {
                margin-left: 25px;
            }
        }
    }

    @media only screen and (max-width: 675px) {
        header {
            height: 108px;

            img {
                margin-left: 15px;
            }
        }
    }
</style>
