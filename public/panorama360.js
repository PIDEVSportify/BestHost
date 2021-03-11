const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 );

const geometry = new THREE.BoxGeometry();
const texture = new  THREE.TextureLoader().load('test360.jpeg');


const material = new THREE.MeshBasicMaterial( { map: texture,
    side: THREE.DoubleSide} );
const cube = new THREE.Mesh( geometry, material );
scene.add( cube );

camera.position.set(1,0,0)  ;


const renderer = new THREE.WebGLRenderer();
renderer.setSize( window.innerWidth, window.innerHeight );
document.body.appendChild( renderer.domElement );

function animate() {
    requestAnimationFrame( animate );

        renderer.render( scene, camera );
}
animate();