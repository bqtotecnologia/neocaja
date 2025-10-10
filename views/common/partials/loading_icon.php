<div class="row w-100 h-auto justify-content-center align-items-center p-5 loader-container d-none" id="loading-container">
    <div class="loader m-auto"></div>
</div>

<style scoped>   
    .loader-contaner{
        min-height: 100px;
    }
    
    .loader {
      width: 120px;
      height:100px;
      aspect-ratio: 1;
      display: grid;
    }
    .loader::before,
    .loader::after {    
      content:"";
      grid-area: 1/1;
      --c:no-repeat radial-gradient(farthest-side,#138496 92%,#0000);
      background: 
        var(--c) 50%  0, 
        var(--c) 50%  100%, 
        var(--c) 100% 50%, 
        var(--c) 0    50%;
      background-size: 24px 24px;
      animation: l12 1s infinite;
    }
    .loader::before {
      margin: 4px;
      filter: hue-rotate(45deg);
      background-size: 16px 16px;
      animation-timing-function: linear
    }
    
    @keyframes l12 { 
      100%{transform: rotate(.5turn)}
    }    
</style>