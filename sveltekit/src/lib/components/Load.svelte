<!-- https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_loader -->

<div></div>

<style>
    div {
        margin-left: auto;
        margin-right: auto;
        margin-top: 12rem;
        border: 16px solid #222;
        border-radius: 50%;
        border-top: 16px solid rgb(124, 10, 10);
        width: 200px;
        height: 200px;
        -webkit-animation: spin 1.5s linear infinite; /* Safari */
        animation: spin 1.5s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
