(()=>{var Gi=Array.isArray,ra=Array.prototype.indexOf,Vt=Array.prototype.includes,na=Array.from,Cr=Object.keys,ir=Object.defineProperty,Bt=Object.getOwnPropertyDescriptor,ia=Object.getOwnPropertyDescriptors,sa=Object.prototype,oa=Array.prototype,Ji=Object.getPrototypeOf,Si=Object.isExtensible,ht=()=>{};function aa(t){for(var e=0;e<t.length;e++)t[e]()}function Zi(){var t,e,r=new Promise((n,i)=>{t=n,e=i});return{promise:r,resolve:t,reject:e}}var he=2,jt=4,Sr=8,xn=1<<24,yt=16,Ze=32,pt=64,ln=128,Ne=512,ue=1024,ve=2048,je=4096,Je=8192,Ve=16384,wt=32768,cn=1<<25,vt=65536,$i=1<<17,la=1<<18,Nt=1<<19,ca=1<<20,$t=65536,un=1<<21,Cn=1<<22,ft=1<<23,Pt=Symbol("$state"),ua=Symbol("legacy props"),ha=Symbol(""),rt=new class extends Error{name="StaleReactionError";message="The reaction that called `getAbortSignal()` was re-run or destroyed"},or=!!globalThis.document?.contentType&&globalThis.document.contentType.includes("xml"),ar=3,lr=8;function Xi(t){return t===this.v}function Qi(t,e){return t!=t?e==e:t!==e||t!==null&&typeof t=="object"||typeof t=="function"}function fa(t){return!Qi(t,this.v)}function da(t){throw new Error("https://svelte.dev/e/lifecycle_outside_component")}function pa(){throw new Error("https://svelte.dev/e/async_derived_orphan")}function va(t){throw new Error("https://svelte.dev/e/effect_in_teardown")}function ga(){throw new Error("https://svelte.dev/e/effect_in_unowned_derived")}function ma(t){throw new Error("https://svelte.dev/e/effect_orphan")}function ba(){throw new Error("https://svelte.dev/e/effect_update_depth_exceeded")}function ya(){throw new Error("https://svelte.dev/e/hydration_failed")}function wa(){throw new Error("https://svelte.dev/e/state_descriptors_fixed")}function _a(){throw new Error("https://svelte.dev/e/state_prototype_fixed")}function Ea(){throw new Error("https://svelte.dev/e/state_unsafe_mutation")}function ka(){throw new Error("https://svelte.dev/e/svelte_boundary_reset_onerror")}var Aa=1,xa=2,On="[",es="[!",Mi="[?",ts="]",Mt={},ce=Symbol(),rs="http://www.w3.org/1999/xhtml",Ca="http://www.w3.org/2000/svg",Oa="http://www.w3.org/1998/Math/MathML",Ta="@attach",Ee=null;function Ut(t){Ee=t}function st(t,e=!1,r){Ee={p:Ee,i:!1,c:null,e:null,s:t,x:null,r:M,l:null}}function ot(t){var e=Ee,r=e.e;if(r!==null){e.e=null;for(var n of r)Us(n)}return t!==void 0&&(e.x=t),e.i=!0,Ee=e.p,t??{}}function ns(){return!0}var xt=[];function is(){var t=xt;xt=[],aa(t)}function it(t){if(xt.length===0&&!tr){var e=xt;queueMicrotask(()=>{e===xt&&is()})}xt.push(t)}function Sa(){for(;xt.length>0;)is()}function cr(t){console.warn("https://svelte.dev/e/hydration_mismatch")}function $a(){console.warn("https://svelte.dev/e/select_multiple_invalid_value")}function Ma(){console.warn("https://svelte.dev/e/svelte_boundary_reset_noop")}var S=!1;function Ge(t){S=t}var D;function be(t){if(t===null)throw cr(),Mt;return D=t}function Ft(){return be(Qe(D))}function W(t){if(S){if(Qe(D)!==null)throw cr(),Mt;D=t}}function Tn(t=1){if(S){for(var e=t,r=D;e--;)r=Qe(r);D=r}}function Sn(t=!0){for(var e=0,r=D;;){if(r.nodeType===lr){var n=r.data;if(n===ts){if(e===0)return r;e-=1}else(n===On||n===es||n[0]==="["&&!isNaN(Number(n.slice(1))))&&(e+=1)}var i=Qe(r);t&&r.remove(),r=i}}function ss(t){if(!t||t.nodeType!==lr)throw cr(),Mt;return t.data}function nt(t){if(typeof t!="object"||t===null||Pt in t)return t;let e=Ji(t);if(e!==sa&&e!==oa)return t;var r=new Map,n=Gi(t),i=F(0),s=St,a=l=>{if(St===s)return l();var c=N,u=St;Le(null),Hi(s);var f=l();return Le(c),Hi(u),f};return n&&r.set("length",F(t.length)),new Proxy(t,{defineProperty(l,c,u){(!("value"in u)||u.configurable===!1||u.enumerable===!1||u.writable===!1)&&wa();var f=r.get(c);return f===void 0?a(()=>{var v=F(u.value);return r.set(c,v),v}):_(f,u.value,!0),!0},deleteProperty(l,c){var u=r.get(c);if(u===void 0){if(c in l){let f=a(()=>F(ce));r.set(c,f),rr(i)}}else _(u,ce),rr(i);return!0},get(l,c,u){if(c===Pt)return t;var f=r.get(c),v=c in l;if(f===void 0&&(!v||Bt(l,c)?.writable)&&(f=a(()=>{var b=nt(v?l[c]:ce),g=F(b);return g}),r.set(c,f)),f!==void 0){var d=o(f);return d===ce?void 0:d}return Reflect.get(l,c,u)},getOwnPropertyDescriptor(l,c){var u=Reflect.getOwnPropertyDescriptor(l,c);if(u&&"value"in u){var f=r.get(c);f&&(u.value=o(f))}else if(u===void 0){var v=r.get(c),d=v?.v;if(v!==void 0&&d!==ce)return{enumerable:!0,configurable:!0,value:d,writable:!0}}return u},has(l,c){if(c===Pt)return!0;var u=r.get(c),f=u!==void 0&&u.v!==ce||Reflect.has(l,c);if(u!==void 0||M!==null&&(!f||Bt(l,c)?.writable)){u===void 0&&(u=a(()=>{var d=f?nt(l[c]):ce,b=F(d);return b}),r.set(c,u));var v=o(u);if(v===ce)return!1}return f},set(l,c,u,f){var v=r.get(c),d=c in l;if(n&&c==="length")for(var b=u;b<v.v;b+=1){var g=r.get(b+"");g!==void 0?_(g,ce):b in l&&(g=a(()=>F(ce)),r.set(b+"",g))}if(v===void 0)(!d||Bt(l,c)?.writable)&&(v=a(()=>F(void 0)),_(v,nt(u)),r.set(c,v));else{d=v.v!==ce;var E=a(()=>nt(u));_(v,E)}var O=Reflect.getOwnPropertyDescriptor(l,c);if(O?.set&&O.set.call(f,u),!d){if(n&&typeof c=="string"){var $=r.get("length"),se=Number(c);Number.isInteger(se)&&se>=$.v&&_($,se+1)}rr(i)}return!0},ownKeys(l){o(i);var c=Reflect.ownKeys(l).filter(v=>{var d=r.get(v);return d===void 0||d.v!==ce});for(var[u,f]of r)f.v!==ce&&!(u in l)&&c.push(u);return c},setPrototypeOf(){_a()}})}function Fi(t){try{if(t!==null&&typeof t=="object"&&Pt in t)return t[Pt]}catch{}return t}function Fa(t,e){return Object.is(Fi(t),Fi(e))}var Tt,hn,os,as,ls;function fn(){if(Tt===void 0){Tt=window,hn=document,os=/Firefox/.test(navigator.userAgent);var t=Element.prototype,e=Node.prototype,r=Text.prototype;as=Bt(e,"firstChild").get,ls=Bt(e,"nextSibling").get,Si(t)&&(t.__click=void 0,t.__className=void 0,t.__attributes=null,t.__style=void 0,t.__e=void 0),Si(r)&&(r.__t=void 0)}}function Ue(t=""){return document.createTextNode(t)}function Oe(t){return as.call(t)}function Qe(t){return ls.call(t)}function Q(t,e){if(!S)return Oe(t);var r=Oe(D);if(r===null)r=D.appendChild(Ue());else if(e&&r.nodeType!==ar){var n=Ue();return r?.before(n),be(n),n}return e&&$r(r),be(r),r}function Dt(t,e=!1){if(!S){var r=Oe(t);return r instanceof Comment&&r.data===""?Qe(r):r}if(e){if(D?.nodeType!==ar){var n=Ue();return D?.before(n),be(n),n}$r(D)}return D}function G(t,e=1,r=!1){let n=S?D:t;for(var i;e--;)i=n,n=Qe(n);if(!S)return n;if(r){if(n?.nodeType!==ar){var s=Ue();return n===null?i?.after(s):n.before(s),be(s),s}$r(n)}return be(n),n}function Na(t){t.textContent=""}function Ia(){return!1}function $n(t,e,r){return document.createElementNS(e??rs,t,void 0)}function $r(t){if(t.nodeValue.length<65536)return;let e=t.nextSibling;for(;e!==null&&e.nodeType===ar;)e.remove(),t.nodeValue+=e.nodeValue,e=t.nextSibling}function cs(t){var e=M;if(e===null)return N.f|=ft,t;if((e.f&wt)===0&&(e.f&jt)===0)throw t;ut(t,e)}function ut(t,e){for(;e!==null;){if((e.f&ln)!==0){if((e.f&wt)===0)throw t;try{e.b.error(t);return}catch(r){t=r}}e=e.parent}throw t}var La=-7169;function re(t,e){t.f=t.f&La|e}function Mn(t){(t.f&Ne)!==0||t.deps===null?re(t,ue):re(t,je)}function us(t){if(t!==null)for(let e of t)(e.f&he)===0||(e.f&$t)===0||(e.f^=$t,us(e.deps))}function hs(t,e,r){(t.f&ve)!==0?e.add(t):(t.f&je)!==0&&r.add(t),us(t.deps),re(t,ue)}function Da(t){return t.endsWith("capture")&&t!=="gotpointercapture"&&t!=="lostpointercapture"}var Ra=["beforeinput","click","change","dblclick","contextmenu","focusin","focusout","input","keydown","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","pointerdown","pointermove","pointerout","pointerover","pointerup","touchend","touchmove","touchstart"];function Ba(t){return Ra.includes(t)}var Pa={formnovalidate:"formNoValidate",ismap:"isMap",nomodule:"noModule",playsinline:"playsInline",readonly:"readOnly",defaultvalue:"defaultValue",defaultchecked:"defaultChecked",srcobject:"srcObject",novalidate:"noValidate",allowfullscreen:"allowFullscreen",disablepictureinpicture:"disablePictureInPicture",disableremoteplayback:"disableRemotePlayback"};function Va(t){return t=t.toLowerCase(),Pa[t]??t}var ja=["touchstart","touchmove"];function Ua(t){return ja.includes(t)}function Ka(t,e){if(e){let r=document.body;t.autofocus=!0,it(()=>{document.activeElement===r&&t.focus()})}}var Ni=!1;function fs(){Ni||(Ni=!0,document.addEventListener("reset",t=>{Promise.resolve().then(()=>{if(!t.defaultPrevented)for(let e of t.target.elements)e.__on_r?.()})},{capture:!0}))}function Mr(t){var e=N,r=M;Le(null),Xe(null);try{return t()}finally{Le(e),Xe(r)}}function za(t,e,r,n=r){t.addEventListener(e,()=>Mr(r));let i=t.__on_r;i?t.__on_r=()=>{i(),n(!0)}:t.__on_r=()=>n(!0),fs()}var Qt=Symbol("events"),ds=new Set,dn=new Set;function ps(t,e,r,n={}){function i(s){if(n.capture||pn.call(e,s),!s.cancelBubble)return Mr(()=>r?.call(this,s))}return t.startsWith("pointer")||t.startsWith("touch")||t==="wheel"?it(()=>{e.addEventListener(t,i,n)}):e.addEventListener(t,i,n),i}function le(t,e,r,n,i){var s={capture:n,passive:i},a=ps(t,e,r,s);(e===document.body||e===window||e===document||e instanceof HTMLMediaElement)&&Dr(()=>{e.removeEventListener(t,a,s)})}function Fr(t,e,r){(e[Qt]??={})[t]=r}function Nr(t){for(var e=0;e<t.length;e++)ds.add(t[e]);for(var r of dn)r(t)}var Ii=null;function pn(t){var e=this,r=e.ownerDocument,n=t.type,i=t.composedPath?.()||[],s=i[0]||t.target;Ii=t;var a=0,l=Ii===t&&t[Qt];if(l){var c=i.indexOf(l);if(c!==-1&&(e===document||e===window)){t[Qt]=e;return}var u=i.indexOf(e);if(u===-1)return;c<=u&&(a=c)}if(s=i[a]||t.target,s!==e){ir(t,"currentTarget",{configurable:!0,get(){return s||r}});var f=N,v=M;Le(null),Xe(null);try{for(var d,b=[];s!==null;){var g=s.assignedSlot||s.parentNode||s.host||null;try{var E=s[Qt]?.[n];E!=null&&(!s.disabled||t.target===s)&&E.call(s,t)}catch(O){d?b.push(O):d=O}if(t.cancelBubble||g===e||g===null)break;s=g}if(d){for(let O of b)queueMicrotask(()=>{throw O});throw d}}finally{t[Qt]=e,delete t.currentTarget,Le(f),Xe(v)}}}var Ha=globalThis?.window?.trustedTypes&&globalThis.window.trustedTypes.createPolicy("svelte-trusted-html",{createHTML:t=>t});function qa(t){return Ha?.createHTML(t)??t}function vs(t){var e=$n("template");return e.innerHTML=qa(t.replaceAll("<!>","<!---->")),e.content}function Te(t,e){var r=M;r.nodes===null&&(r.nodes={start:t,end:e,a:null,t:null})}function Z(t,e){var r=(e&Aa)!==0,n=(e&xa)!==0,i,s=!t.startsWith("<!>");return()=>{if(S)return Te(D,null),D;i===void 0&&(i=vs(s?t:"<!>"+t),r||(i=Oe(i)));var a=n||os?document.importNode(i,!0):i.cloneNode(!0);if(r){var l=Oe(a),c=a.lastChild;Te(l,c)}else Te(a,a);return a}}function Ya(t,e,r="svg"){var n=!t.startsWith("<!>"),i=`<${r}>${n?t:"<!>"+t}</${r}>`,s;return()=>{if(S)return Te(D,null),D;if(!s){var a=vs(i),l=Oe(a);s=Oe(l)}var c=s.cloneNode(!0);return Te(c,c),c}}function Fn(t,e){return Ya(t,e,"svg")}function _r(t=""){if(!S){var e=Ue(t+"");return Te(e,e),e}var r=D;return r.nodeType!==ar?(r.before(r=Ue()),be(r)):$r(r),Te(r,r),r}function Li(){if(S)return Te(D,null),D;var t=document.createDocumentFragment(),e=document.createComment(""),r=Ue();return t.append(e,r),Te(e,r),t}function L(t,e){if(S){var r=M;((r.f&wt)===0||r.nodes.end===null)&&(r.nodes.end=D),Ft();return}t!==null&&t.before(e)}function Wa(t){let e=0,r=ur(0),n;return()=>{Pn()&&(o(r),Rr(()=>(e===0&&(n=fr(()=>t(()=>rr(r)))),e+=1,()=>{it(()=>{e-=1,e===0&&(n?.(),n=void 0,rr(r))})})))}}var Ga=vt|Nt;function Ja(t,e,r,n){new vn(t,e,r,n)}var vn=class{parent;is_pending=!1;transform_error;#e;#t=S?D:null;#r;#l;#i;#n=null;#s=null;#o=null;#a=null;#c=0;#u=0;#f=!1;#d=new Set;#p=new Set;#h=null;#y=Wa(()=>(this.#h=ur(this.#c),()=>{this.#h=null}));constructor(e,r,n,i){this.#e=e,this.#r=r,this.#l=s=>{var a=M;a.b=this,a.f|=ln,n(s)},this.parent=M.b,this.transform_error=i??this.parent?.transform_error??(s=>s),this.#i=dr(()=>{if(S){let s=this.#t;Ft();let a=s.data===es;if(s.data.startsWith(Mi)){let c=JSON.parse(s.data.slice(Mi.length));this.#_(c)}else a?this.#E():this.#w()}else this.#m()},Ga),S&&(this.#e=D)}#w(){try{this.#n=Re(()=>this.#l(this.#e))}catch(e){this.error(e)}}#_(e){let r=this.#r.failed;r&&(this.#o=Re(()=>{r(this.#e,()=>e,()=>()=>{})}))}#E(){let e=this.#r.pending;if(e){this.is_pending=!0,this.#s=Re(()=>e(this.#e));var r=V;it(()=>{var n=this.#a=document.createDocumentFragment(),i=Ue();n.append(i),this.#n=this.#g(()=>Re(()=>this.#l(i))),this.#u===0&&(this.#e.before(n),this.#a=null,nr(this.#s,()=>{this.#s=null}),this.#v(r))})}}#m(){var e=V;try{if(this.is_pending=this.has_pending_snippet(),this.#u=0,this.#c=0,this.#n=Re(()=>{this.#l(this.#e)}),this.#u>0){var r=this.#a=document.createDocumentFragment();Gs(this.#n,r);let n=this.#r.pending;this.#s=Re(()=>n(this.#e))}else this.#v(e)}catch(n){this.error(n)}}#v(e){this.is_pending=!1;for(let r of this.#d)re(r,ve),e.schedule(r);for(let r of this.#p)re(r,je),e.schedule(r);this.#d.clear(),this.#p.clear()}defer_effect(e){hs(e,this.#d,this.#p)}is_rendered(){return!this.is_pending&&(!this.parent||this.parent.is_rendered())}has_pending_snippet(){return!!this.#r.pending}#g(e){var r=M,n=N,i=Ee;Xe(this.#i),Le(this.#i),Ut(this.#i.ctx);try{return mt.ensure(),e()}catch(s){return cs(s),null}finally{Xe(r),Le(n),Ut(i)}}#b(e,r){if(!this.has_pending_snippet()){this.parent&&this.parent.#b(e,r);return}this.#u+=e,this.#u===0&&(this.#v(r),this.#s&&nr(this.#s,()=>{this.#s=null}),this.#a&&(this.#e.before(this.#a),this.#a=null))}update_pending_count(e,r){this.#b(e,r),this.#c+=e,!(!this.#h||this.#f)&&(this.#f=!0,it(()=>{this.#f=!1,this.#h&&Tr(this.#h,this.#c)}))}get_effect_pending(){return this.#y(),o(this.#h)}error(e){var r=this.#r.onerror;let n=this.#r.failed;if(!r&&!n)throw e;this.#n&&(de(this.#n),this.#n=null),this.#s&&(de(this.#s),this.#s=null),this.#o&&(de(this.#o),this.#o=null),S&&(be(this.#t),Tn(),be(Sn()));var i=!1,s=!1;let a=()=>{if(i){Ma();return}i=!0,s&&ka(),this.#o!==null&&nr(this.#o,()=>{this.#o=null}),this.#g(()=>{this.#m()})},l=c=>{try{s=!0,r?.(c,a),s=!1}catch(u){ut(u,this.#i&&this.#i.parent)}n&&(this.#o=this.#g(()=>{try{return Re(()=>{var u=M;u.b=this,u.f|=ln,n(this.#e,()=>c,()=>a)})}catch(u){return ut(u,this.#i.parent),null}}))};it(()=>{var c;try{c=this.transform_error(e)}catch(u){ut(u,this.#i&&this.#i.parent);return}c!==null&&typeof c=="object"&&typeof c.then=="function"?c.then(l,u=>ut(u,this.#i&&this.#i.parent)):l(c)})}};function We(t,e){var r=e==null?"":typeof e=="object"?`${e}`:e;r!==(t.__t??=t.nodeValue)&&(t.__t=r,t.nodeValue=`${r}`)}function gs(t,e){return ms(t,e)}function Za(t,e){fn(),e.intro=e.intro??!1;let r=e.target,n=S,i=D;try{for(var s=Oe(r);s&&(s.nodeType!==lr||s.data!==On);)s=Qe(s);if(!s)throw Mt;Ge(!0),be(s);let a=ms(t,{...e,anchor:s});return Ge(!1),a}catch(a){if(a instanceof Error&&a.message.split(`
`).some(l=>l.startsWith("https://svelte.dev/e/")))throw a;return a!==Mt&&console.warn("Failed to hydrate: ",a),e.recover===!1&&ya(),fn(),Na(r),Ge(!1),gs(t,e)}finally{Ge(n),be(i)}}var Er=new Map;function ms(t,{target:e,anchor:r,props:n={},events:i,context:s,intro:a=!0,transformError:l}){fn();var c=void 0,u=Bl(()=>{var f=r??e.appendChild(Ue());Ja(f,{pending:()=>{}},b=>{st({});var g=Ee;if(s&&(g.c=s),i&&(n.$$events=i),S&&Te(b,null),c=t(b,n)||{},S&&(M.nodes.end=D,D===null||D.nodeType!==lr||D.data!==ts))throw cr(),Mt;ot()},l);var v=new Set,d=b=>{for(var g=0;g<b.length;g++){var E=b[g];if(!v.has(E)){v.add(E);var O=Ua(E);for(let ne of[e,document]){var $=Er.get(ne);$===void 0&&($=new Map,Er.set(ne,$));var se=$.get(E);se===void 0?(ne.addEventListener(E,pn,{passive:O}),$.set(E,1)):$.set(E,se+1)}}}};return d(na(ds)),dn.add(d),()=>{for(var b of v)for(let O of[e,document]){var g=Er.get(O),E=g.get(b);--E==0?(O.removeEventListener(b,pn),g.delete(b),g.size===0&&Er.delete(O)):g.set(b,E)}dn.delete(d),f!==r&&f.parentNode?.removeChild(f)}});return gn.set(c,u),c}var gn=new WeakMap;function Xa(t,e){let r=gn.get(t);return r?(gn.delete(t),r(e)):Promise.resolve()}function Nn(t){var e=he|ve,r=N!==null&&(N.f&he)!==0?N:null;return M!==null&&(M.f|=Nt),{ctx:Ee,deps:null,effects:null,equals:Xi,f:e,fn:t,reactions:null,rv:0,v:ce,wv:0,parent:r??M,ac:null}}function Qa(t,e,r){let n=M;n===null&&pa();var i=void 0,s=ur(ce),a=!N,l=new Map;return Pl(()=>{var c=M,u=Zi();i=u.promise;try{Promise.resolve(t()).then(u.resolve,u.reject).finally(Or)}catch(b){u.reject(b),Or()}var f=V;if(a){if((c.f&wt)!==0)var v=_s();if(n.b.is_rendered())l.get(f)?.reject(rt),l.delete(f);else{for(let b of l.values())b.reject(rt);l.clear()}l.set(f,u)}let d=(b,g=void 0)=>{if(v){var E=g===rt;v(E)}if(!(g===rt||(c.f&Ve)!==0)){if(f.activate(),g)s.f|=ft,Tr(s,g);else{(s.f&ft)!==0&&(s.f^=ft),Tr(s,b);for(let[O,$]of l){if(l.delete(O),O===f)break;$.reject(rt)}}f.deactivate()}};u.promise.then(d,b=>d(null,b||"unknown"))}),Dr(()=>{for(let c of l.values())c.reject(rt)}),new Promise(c=>{function u(f){function v(){f===i?c(s):u(i)}f.then(v,v)}u(i)})}function me(t){let e=Nn(t);return Ls(e),e}function el(t){var e=t.effects;if(e!==null){t.effects=null;for(var r=0;r<e.length;r+=1)de(e[r])}}function tl(t){for(var e=t.parent;e!==null;){if((e.f&he)===0)return(e.f&Ve)===0?e:null;e=e.parent}return null}function In(t){var e,r=M;Xe(tl(t));try{t.f&=~$t,el(t),e=Ps(t)}finally{Xe(r)}return e}function bs(t){var e=In(t);if(!t.equals(e)&&(t.wv=Rs(),(!V?.is_fork||t.deps===null)&&(t.v=e,t.deps===null))){re(t,ue);return}bt||(Be!==null?(Pn()||V?.is_fork)&&Be.set(t,e):Mn(t))}function rl(t){if(t.effects!==null)for(let e of t.effects)(e.teardown||e.ac)&&(e.teardown?.(),e.ac?.abort(rt),e.teardown=ht,e.ac=null,sr(e,0),jn(e))}function ys(t){if(t.effects!==null)for(let e of t.effects)e.teardown&&zt(e)}function ws(t,e,r,n){let i=Nn;var s=t.filter(d=>!d.settled);if(r.length===0&&s.length===0){n(e.map(i));return}var a=M,l=nl(),c=s.length===1?s[0].promise:s.length>1?Promise.all(s.map(d=>d.promise)):null;function u(d){l();try{n(d)}catch(b){(a.f&Ve)===0&&ut(b,a)}Or()}if(r.length===0){c.then(()=>u(e.map(i)));return}var f=_s();function v(){Promise.all(r.map(d=>Qa(d))).then(d=>u([...e.map(i),...d])).catch(d=>ut(d,a)).finally(()=>f())}c?c.then(()=>{l(),v(),Or()}):v()}function nl(){var t=M,e=N,r=Ee,n=V;return function(s=!0){Xe(t),Le(e),Ut(r),s&&(t.f&Ve)===0&&(n?.activate(),n?.apply())}}function Or(t=!0){Xe(null),Le(null),Ut(null),t&&V?.deactivate()}function _s(){var t=M.b,e=V,r=t.is_rendered();return t.update_pending_count(1,e),e.increment(r),(n=!1)=>{t.update_pending_count(-1,e),e.decrement(r,n)}}var Kt=class{anchor;#e=new Map;#t=new Map;#r=new Map;#l=new Set;#i=!0;constructor(e,r=!0){this.anchor=e,this.#i=r}#n=e=>{if(this.#e.has(e)){var r=this.#e.get(e),n=this.#t.get(r);if(n)jl(n),this.#l.delete(r);else{var i=this.#r.get(r);i&&(this.#t.set(r,i.effect),this.#r.delete(r),i.fragment.lastChild.remove(),this.anchor.before(i.fragment),n=i.effect)}for(let[s,a]of this.#e){if(this.#e.delete(s),s===e)break;let l=this.#r.get(a);l&&(de(l.effect),this.#r.delete(a))}for(let[s,a]of this.#t){if(s===r||this.#l.has(s))continue;let l=()=>{if(Array.from(this.#e.values()).includes(s)){var u=document.createDocumentFragment();Gs(a,u),u.append(Ue()),this.#r.set(s,{effect:a,fragment:u})}else de(a);this.#l.delete(s),this.#t.delete(s)};this.#i||!n?(this.#l.add(s),nr(a,l,!1)):l()}}};#s=e=>{this.#e.delete(e);let r=Array.from(this.#e.values());for(let[n,i]of this.#r)r.includes(n)||(de(i.effect),this.#r.delete(n))};ensure(e,r){var n=V,i=Ia();if(r&&!this.#t.has(e)&&!this.#r.has(e))if(i){var s=document.createDocumentFragment(),a=Ue();s.append(a),this.#r.set(e,{effect:Re(()=>r(a)),fragment:s})}else this.#t.set(e,Re(()=>r(this.anchor)));if(this.#e.set(n,e),i){for(let[l,c]of this.#t)l===e?n.unskip_effect(c):n.skip_effect(c);for(let[l,c]of this.#r)l===e?n.unskip_effect(c.effect):n.skip_effect(c.effect);n.oncommit(this.#n),n.ondiscard(this.#s)}else S&&(this.anchor=D),this.#n(n)}};function ae(t,e,r=!1){var n;S&&(n=D,Ft());var i=new Kt(t),s=r?vt:0;function a(l,c){if(S){var u=ss(n);if(l!==parseInt(u.substring(1))){var f=Sn();be(f),i.anchor=f,Ge(!1),i.ensure(l,c),Ge(!0);return}}i.ensure(l,c)}dr(()=>{var l=!1;e((c,u=0)=>{l=!0,a(u,c)}),l||a(-1,null)},s)}var il=Symbol("NaN");function sl(t,e,r){S&&Ft();var n=new Kt(t);dr(()=>{var i=e();i!==i&&(i=il),n.ensure(i,r)})}function Es(t,e,r=!1,n=!1,i=!1,s=!1){var a=t,l="";if(r){var c=t;S&&(a=be(Oe(c)))}pe(()=>{var u=M;if(l===(l=e()??"")){S&&Ft();return}if(r&&!S){u.nodes=null,c.innerHTML=l,l!==""&&Te(Oe(c),c.lastChild);return}if(u.nodes!==null&&(Hs(u.nodes.start,u.nodes.end),u.nodes=null),l!==""){if(S){D.data;for(var f=Ft(),v=f;f!==null&&(f.nodeType!==lr||f.data!=="");)v=f,f=Qe(f);if(f===null)throw cr(),Mt;Te(D,v),a=be(f);return}var d=n?Ca:i?Oa:void 0,b=$n(n?"svg":i?"math":"template",d);b.innerHTML=l;var g=n||i?b:b.content;if(Te(Oe(g),g.lastChild),n||i)for(;Oe(g);)a.before(Oe(g));else a.before(g)}})}function ol(t,e,...r){var n=new Kt(t);dr(()=>{let i=e()??null;n.ensure(i,i&&(s=>i(s,...r)))},vt)}function al(t,e,r){var n;S&&(n=D,Ft());var i=new Kt(t);dr(()=>{var s=e()??null;if(S){var a=ss(n),l=a===On,c=s!==null;if(l!==c){var u=Sn();be(u),i.anchor=u,Ge(!1),i.ensure(s,s&&(f=>r(f,s))),Ge(!0);return}}i.ensure(s,s&&(f=>r(f,s)))},vt)}function ll(t,e){var r=void 0,n;Ks(()=>{r!==(r=e())&&(n&&(de(n),n=null),r&&(n=Re(()=>{Vn(()=>r(t))})))})}function ks(t){var e,r,n="";if(typeof t=="string"||typeof t=="number")n+=t;else if(typeof t=="object")if(Array.isArray(t)){var i=t.length;for(e=0;e<i;e++)t[e]&&(r=ks(t[e]))&&(n&&(n+=" "),n+=r)}else for(r in t)t[r]&&(n&&(n+=" "),n+=r);return n}function cl(){for(var t,e,r=0,n="",i=arguments.length;r<i;r++)(t=arguments[r])&&(e=ks(t))&&(n&&(n+=" "),n+=e);return n}function ul(t){return typeof t=="object"?cl(t):t??""}var Di=[...` 	
\r\f\xA0\v\uFEFF`];function hl(t,e,r){var n=t==null?"":""+t;if(r){for(var i of Object.keys(r))if(r[i])n=n?n+" "+i:i;else if(n.length)for(var s=i.length,a=0;(a=n.indexOf(i,a))>=0;){var l=a+s;(a===0||Di.includes(n[a-1]))&&(l===n.length||Di.includes(n[l]))?n=(a===0?"":n.substring(0,a))+n.substring(l+1):a=l}}return n===""?null:n}function Ri(t,e=!1){var r=e?" !important;":";",n="";for(var i of Object.keys(t)){var s=t[i];s!=null&&s!==""&&(n+=" "+i+": "+s+r)}return n}function rn(t){return t[0]!=="-"||t[1]!=="-"?t.toLowerCase():t}function fl(t,e){if(e){var r="",n,i;if(Array.isArray(e)?(n=e[0],i=e[1]):n=e,t){t=String(t).replaceAll(/\s*\/\*.*?\*\/\s*/g,"").trim();var s=!1,a=0,l=!1,c=[];n&&c.push(...Object.keys(n).map(rn)),i&&c.push(...Object.keys(i).map(rn));var u=0,f=-1;let E=t.length;for(var v=0;v<E;v++){var d=t[v];if(l?d==="/"&&t[v-1]==="*"&&(l=!1):s?s===d&&(s=!1):d==="/"&&t[v+1]==="*"?l=!0:d==='"'||d==="'"?s=d:d==="("?a++:d===")"&&a--,!l&&s===!1&&a===0){if(d===":"&&f===-1)f=v;else if(d===";"||v===E-1){if(f!==-1){var b=rn(t.substring(u,f).trim());if(!c.includes(b)){d!==";"&&v++;var g=t.substring(u,v).trim();r+=" "+g+";"}}u=v+1,f=-1}}}}return n&&(r+=Ri(n)),i&&(r+=Ri(i,!0)),r=r.trim(),r===""?null:r}return t==null?null:String(t)}function dl(t,e,r,n,i,s){var a=t.__className;if(S||a!==r||a===void 0){var l=hl(r,n,s);(!S||l!==t.getAttribute("class"))&&(l==null?t.removeAttribute("class"):e?t.className=l:t.setAttribute("class",l)),t.__className=r}else if(s&&i!==s)for(var c in s){var u=!!s[c];(i==null||u!==!!i[c])&&t.classList.toggle(c,u)}return s}function nn(t,e={},r,n){for(var i in r){var s=r[i];e[i]!==s&&(r[i]==null?t.style.removeProperty(i):t.style.setProperty(i,s,n))}}function pl(t,e,r,n){var i=t.__style;if(S||i!==e){var s=fl(e,n);(!S||s!==t.getAttribute("style"))&&(s==null?t.removeAttribute("style"):t.style.cssText=s),t.__style=e}else n&&(Array.isArray(n)?(nn(t,r?.[0],n[0]),nn(t,r?.[1],n[1],"important")):nn(t,r,n));return n}function mn(t,e,r=!1){if(t.multiple){if(e==null)return;if(!Gi(e))return $a();for(var n of t.options)n.selected=e.includes(Bi(n));return}for(n of t.options){var i=Bi(n);if(Fa(i,e)){n.selected=!0;return}}(!r||e!==void 0)&&(t.selectedIndex=-1)}function vl(t){var e=new MutationObserver(()=>{mn(t,t.__value)});e.observe(t,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["value"]}),Dr(()=>{e.disconnect()})}function Bi(t){return"__value"in t?t.__value:t.value}var Jt=Symbol("class"),Zt=Symbol("style"),As=Symbol("is custom element"),xs=Symbol("is html"),gl=or?"link":"LINK",ml=or?"input":"INPUT",bl=or?"option":"OPTION",yl=or?"select":"SELECT",wl=or?"progress":"PROGRESS";function Ln(t){if(S){var e=!1,r=()=>{if(!e){if(e=!0,t.hasAttribute("value")){var n=t.value;U(t,"value",null),t.value=n}if(t.hasAttribute("checked")){var i=t.checked;U(t,"checked",null),t.checked=i}}};t.__on_r=r,it(r),fs()}}function _l(t,e){var r=Dn(t);r.value===(r.value=e??void 0)||t.value===e&&(e!==0||t.nodeName!==wl)||(t.value=e??"")}function El(t,e){e?t.hasAttribute("selected")||t.setAttribute("selected",""):t.removeAttribute("selected")}function U(t,e,r,n){var i=Dn(t);S&&(i[e]=t.getAttribute(e),e==="src"||e==="srcset"||e==="href"&&t.nodeName===gl)||i[e]!==(i[e]=r)&&(e==="loading"&&(t[ha]=r),r==null?t.removeAttribute(e):typeof r!="string"&&Cs(t).includes(e)?t[e]=r:t.setAttribute(e,r))}function kl(t,e,r,n,i=!1,s=!1){if(S&&i&&t.nodeName===ml){var a=t,l=a.type==="checkbox"?"defaultChecked":"defaultValue";l in r||Ln(a)}var c=Dn(t),u=c[As],f=!c[xs];let v=S&&u;v&&Ge(!1);var d=e||{},b=t.nodeName===bl;for(var g in e)g in r||(r[g]=null);r.class?r.class=ul(r.class):r[Jt]&&(r.class=null),r[Zt]&&(r.style??=null);var E=Cs(t);for(let A in r){let I=r[A];if(b&&A==="value"&&I==null){t.value=t.__value="",d[A]=I;continue}if(A==="class"){var O=t.namespaceURI==="http://www.w3.org/1999/xhtml";dl(t,O,I,n,e?.[Jt],r[Jt]),d[A]=I,d[Jt]=r[Jt];continue}if(A==="style"){pl(t,I,e?.[Zt],r[Zt]),d[A]=I,d[Zt]=r[Zt];continue}var $=d[A];if(!(I===$&&!(I===void 0&&t.hasAttribute(A)))){d[A]=I;var se=A[0]+A[1];if(se!=="$$")if(se==="on"){let ee={},B="$$"+A,R=A.slice(2);var ne=Ba(R);if(Da(R)&&(R=R.slice(0,-7),ee.capture=!0),!ne&&$){if(I!=null)continue;t.removeEventListener(R,d[B],ee),d[B]=null}if(ne)Fr(R,t,I),Nr([R]);else if(I!=null){let De=function(ge){d[A].call(this,ge)};var oe=De;d[B]=ps(R,t,De,ee)}}else if(A==="style")U(t,A,I);else if(A==="autofocus")Ka(t,!!I);else if(!u&&(A==="__value"||A==="value"&&I!=null))t.value=t.__value=I;else if(A==="selected"&&b)El(t,I);else{var K=A;f||(K=Va(K));var ze=K==="defaultValue"||K==="defaultChecked";if(I==null&&!u&&!ze)if(c[A]=null,K==="value"||K==="checked"){let ee=t,B=e===void 0;if(K==="value"){let R=ee.defaultValue;ee.removeAttribute(K),ee.defaultValue=R,ee.value=ee.__value=B?R:null}else{let R=ee.defaultChecked;ee.removeAttribute(K),ee.defaultChecked=R,ee.checked=B?R:!1}}else t.removeAttribute(A);else ze||E.includes(K)&&(u||typeof I!="string")?(t[K]=I,K in c&&(c[K]=ce)):typeof I!="function"&&U(t,K,I)}}}return v&&Ge(!0),d}function Ir(t,e,r=[],n=[],i=[],s,a=!1,l=!1){ws(i,r,n,c=>{var u=void 0,f={},v=t.nodeName===yl,d=!1;if(Ks(()=>{var g=e(...c.map(o)),E=kl(t,u,g,s,a,l);d&&v&&"value"in g&&mn(t,g.value);for(let $ of Object.getOwnPropertySymbols(f))g[$]||de(f[$]);for(let $ of Object.getOwnPropertySymbols(g)){var O=g[$];$.description===Ta&&(!u||O!==u[$])&&(f[$]&&de(f[$]),f[$]=Re(()=>ll(t,()=>O))),E[$]=O}u=E}),v){var b=t;Vn(()=>{mn(b,u.value,!0),vl(b)})}d=!0})}function Dn(t){return t.__attributes??={[As]:t.nodeName.includes("-"),[xs]:t.namespaceURI===rs}}var Pi=new Map;function Cs(t){var e=t.getAttribute("is")||t.nodeName,r=Pi.get(e);if(r)return r;Pi.set(e,r=[]);for(var n,i=t,s=Element.prototype;s!==i;){n=ia(i);for(var a in n)n[a].set&&r.push(a);i=Ji(i)}return r}function Al(t,e,r=e){var n=new WeakSet;za(t,"input",async i=>{var s=i?t.defaultValue:t.value;if(s=sn(t)?on(s):s,r(s),V!==null&&n.add(V),await Ot(),s!==(s=e())){var a=t.selectionStart,l=t.selectionEnd,c=t.value.length;if(t.value=s??"",l!==null){var u=t.value.length;a===l&&l===c&&u>c?(t.selectionStart=u,t.selectionEnd=u):(t.selectionStart=a,t.selectionEnd=Math.min(l,u))}}}),(S&&t.defaultValue!==t.value||fr(e)==null&&t.value)&&(r(sn(t)?on(t.value):t.value),V!==null&&n.add(V)),Rr(()=>{var i=e();if(t===document.activeElement){var s=V;if(n.has(s))return}sn(t)&&i===on(t.value)||t.type==="date"&&!i&&!t.value||i!==t.value&&(t.value=i??"")})}function sn(t){var e=t.type;return e==="number"||e==="range"}function on(t){return t===""?null:+t}function Vi(t,e){return t===e||t?.[Pt]===e}function gt(t={},e,r,n){var i=Ee.r,s=M;return Vn(()=>{var a,l;return Rr(()=>{a=l,l=[],fr(()=>{t!==r(...l)&&(e(t,...l),a&&Vi(r(...a),t)&&e(null,...a))})}),()=>{let c=s;for(;c!==i&&c.parent!==null&&c.parent.f&cn;)c=c.parent;let u=()=>{l&&Vi(r(...l),t)&&e(null,...l)},f=c.teardown;c.teardown=()=>{u(),f?.()}}}),t}var xl={get(t,e){if(!t.exclude.includes(e))return t.props[e]},set(t,e){return!1},getOwnPropertyDescriptor(t,e){if(!t.exclude.includes(e)&&e in t.props)return{enumerable:!0,configurable:!0,value:t.props[e]}},has(t,e){return t.exclude.includes(e)?!1:e in t.props},ownKeys(t){return Reflect.ownKeys(t.props).filter(e=>!t.exclude.includes(e))}};function Lr(t,e,r){return new Proxy({props:t,exclude:e},xl)}function J(t,e,r,n){var i=n,s=!0,a=()=>(s&&(s=!1,i=n),i),l;l=t[e],l===void 0&&n!==void 0&&(l=a());var c;c=()=>{var d=t[e];return d===void 0?a():(s=!0,d)};var u=!1,f=Nn(()=>(u=!1,c())),v=M;return(function(d,b){if(arguments.length>0){let g=b?o(f):d;return _(f,g),u=!0,i!==void 0&&(i=g),d}return bt&&u||(v.f&Ve)!==0?f.v:o(f)})}function Cl(t){return new bn(t)}var bn=class{#e;#t;constructor(e){var r=new Map,n=(s,a)=>{var l=Ns(a,!1,!1);return r.set(s,l),l};let i=new Proxy({...e.props||{},$$events:{}},{get(s,a){return o(r.get(a)??n(a,Reflect.get(s,a)))},has(s,a){return a===ua?!0:(o(r.get(a)??n(a,Reflect.get(s,a))),Reflect.has(s,a))},set(s,a,l){return _(r.get(a)??n(a,l),l),Reflect.set(s,a,l)}});this.#t=(e.hydrate?Za:gs)(e.component,{target:e.target,anchor:e.anchor,props:i,context:e.context,intro:e.intro??!1,recover:e.recover,transformError:e.transformError}),(!e?.props?.$$host||e.sync===!1)&&q(),this.#e=i.$$events;for(let s of Object.keys(this.#t))s==="$set"||s==="$destroy"||s==="$on"||ir(this,s,{get(){return this.#t[s]},set(a){this.#t[s]=a},enumerable:!0});this.#t.$set=s=>{Object.assign(i,s)},this.#t.$destroy=()=>{Xa(this.#t)}}$set(e){this.#t.$set(e)}$on(e,r){this.#e[e]=this.#e[e]||[];let n=(...i)=>r.call(this,...i);return this.#e[e].push(n),()=>{this.#e[e]=this.#e[e].filter(i=>i!==n)}}$destroy(){this.#t.$destroy()}},Os=class{};typeof HTMLElement=="function"&&(Os=class extends HTMLElement{$$ctor;$$s;$$c;$$cn=!1;$$d={};$$r=!1;$$p_d={};$$l={};$$l_u=new Map;$$me;$$shadowRoot=null;constructor(t,e,r){super(),this.$$ctor=t,this.$$s=e,r&&(this.$$shadowRoot=this.attachShadow(r))}addEventListener(t,e,r){if(this.$$l[t]=this.$$l[t]||[],this.$$l[t].push(e),this.$$c){let n=this.$$c.$on(t,e);this.$$l_u.set(e,n)}super.addEventListener(t,e,r)}removeEventListener(t,e,r){if(super.removeEventListener(t,e,r),this.$$c){let n=this.$$l_u.get(e);n&&(n(),this.$$l_u.delete(e))}}async connectedCallback(){if(this.$$cn=!0,!this.$$c){let e=function(i){return s=>{let a=$n("slot");i!=="default"&&(a.name=i),L(s,a)}};var t=e;if(await Promise.resolve(),!this.$$cn||this.$$c)return;let r={},n=Ol(this);for(let i of this.$$s)i in n&&(i==="default"&&!this.$$d.children?(this.$$d.children=e(i),r.default=!0):r[i]=e(i));for(let i of this.attributes){let s=this.$$g_p(i.name);s in this.$$d||(this.$$d[s]=kr(s,i.value,this.$$p_d,"toProp"))}for(let i in this.$$p_d)!(i in this.$$d)&&this[i]!==void 0&&(this.$$d[i]=this[i],delete this[i]);this.$$c=Cl({component:this.$$ctor,target:this.$$shadowRoot||this,props:{...this.$$d,$$slots:r,$$host:this}}),this.$$me=Rl(()=>{Rr(()=>{this.$$r=!0;for(let i of Cr(this.$$c)){if(!this.$$p_d[i]?.reflect)continue;this.$$d[i]=this.$$c[i];let s=kr(i,this.$$d[i],this.$$p_d,"toAttribute");s==null?this.removeAttribute(this.$$p_d[i].attribute||i):this.setAttribute(this.$$p_d[i].attribute||i,s)}this.$$r=!1})});for(let i in this.$$l)for(let s of this.$$l[i]){let a=this.$$c.$on(i,s);this.$$l_u.set(s,a)}this.$$l={}}}attributeChangedCallback(t,e,r){this.$$r||(t=this.$$g_p(t),this.$$d[t]=kr(t,r,this.$$p_d,"toProp"),this.$$c?.$set({[t]:this.$$d[t]}))}disconnectedCallback(){this.$$cn=!1,Promise.resolve().then(()=>{!this.$$cn&&this.$$c&&(this.$$c.$destroy(),this.$$me(),this.$$c=void 0)})}$$g_p(t){return Cr(this.$$p_d).find(e=>this.$$p_d[e].attribute===t||!this.$$p_d[e].attribute&&e.toLowerCase()===t)||t}});function kr(t,e,r,n){let i=r[t]?.type;if(e=i==="Boolean"&&typeof e!="boolean"?e!=null:e,!n||!r[t])return e;if(n==="toAttribute")switch(i){case"Object":case"Array":return e==null?null:JSON.stringify(e);case"Boolean":return e?"":null;case"Number":return e??null;default:return e}else switch(i){case"Object":case"Array":return e&&JSON.parse(e);case"Boolean":return e;case"Number":return e!=null?+e:e;default:return e}}function Ol(t){let e={};return t.childNodes.forEach(r=>{e[r.slot||"default"]=!0}),e}function _t(t,e,r,n,i,s){let a=class extends Os{constructor(){super(t,r,i),this.$$p_d=e}static get observedAttributes(){return Cr(e).map(l=>(e[l].attribute||l).toLowerCase())}};return Cr(e).forEach(l=>{ir(a.prototype,l,{get(){return this.$$c&&l in this.$$c?this.$$c[l]:this.$$d[l]},set(c){c=kr(l,c,e),this.$$d[l]=c;var u=this.$$c;if(u){var f=Bt(u,l)?.get;f?u[l]=c:u.$set({[l]:c})}}})}),n.forEach(l=>{ir(a.prototype,l,{get(){return this.$$c?.[l]}})}),t.element=a,a}function Rn(t){Ee===null&&da(),Ce(()=>{let e=fr(t);if(typeof e=="function")return e})}function Ts(t,e,r){if(t==null)return e(void 0),ht;let n=fr(()=>t.subscribe(e,r));return n.unsubscribe?()=>n.unsubscribe():n}var Lt=[];function Tl(t,e=ht){let r=null,n=new Set;function i(l){if(Qi(t,l)&&(t=l,r)){let c=!Lt.length;for(let u of n)u[1](),Lt.push(u,t);if(c){for(let u=0;u<Lt.length;u+=2)Lt[u][0](Lt[u+1]);Lt.length=0}}}function s(l){i(l(t))}function a(l,c=ht){let u=[l,c];return n.add(u),n.size===1&&(r=e(i,s)||ht),l(t),()=>{n.delete(u),n.size===0&&r&&(r(),r=null)}}return{set:i,update:s,subscribe:a}}function er(t){let e;return Ts(t,r=>e=r)(),e}var yn=Symbol();function ji(t,e,r){let n=r[e]??={store:null,source:Ns(void 0),unsubscribe:ht};if(n.store!==t&&!(yn in r))if(n.unsubscribe(),n.store=t??null,t==null)n.source.v=void 0,n.unsubscribe=ht;else{var i=!0;n.unsubscribe=Ts(t,s=>{i?n.source.v=s:_(n.source,s)}),i=!1}return t&&yn in r?er(t):o(n.source)}function Sl(){let t={};function e(){Dr(()=>{for(var r in t)t[r].unsubscribe();ir(t,yn,{enumerable:!1,value:!0})})}return[t,e]}var Xt=new Set,V=null,Be=null,wn=null,tr=!1,an=!1,Rt=null,Ar=null,Ui=0,$l=1,mt=class t{id=$l++;current=new Map;previous=new Map;#e=new Set;#t=new Set;#r=0;#l=0;#i=null;#n=[];#s=new Set;#o=new Set;#a=new Map;is_fork=!1;#c=!1;#u(){return this.is_fork||this.#l>0}skip_effect(e){this.#a.has(e)||this.#a.set(e,{d:[],m:[]})}unskip_effect(e){var r=this.#a.get(e);if(r){this.#a.delete(e);for(var n of r.d)re(n,ve),this.schedule(n);for(n of r.m)re(n,je),this.schedule(n)}}#f(){Ui++>1e3&&Ml();let e=this.#n;this.#n=[],this.apply();var r=Rt=[],n=[],i=Ar=[];for(let l of e)this.#d(l,r,n);if(V=null,i.length>0){var s=t.ensure();for(let l of i)s.schedule(l)}if(Rt=null,Ar=null,this.#u()){this.#p(n),this.#p(r);for(let[l,c]of this.#a)Ms(l,c)}else{this.#s.clear(),this.#o.clear();for(let l of this.#e)l(this);this.#e.clear(),Ki(n),Ki(r),this.#r===0&&this.#h(),this.#i?.resolve()}var a=V;if(this.#n.length>0){let l=a??=this;l.#n.push(...this.#n.filter(c=>!l.#n.includes(c)))}a!==null&&(Xt.add(a),a.#f())}#d(e,r,n){e.f^=ue;for(var i=e.first;i!==null;){var s=i.f,a=(s&(Ze|pt))!==0,l=a&&(s&ue)!==0,c=l||(s&Je)!==0||this.#a.has(i);if(!c&&i.fn!==null){a?i.f^=ue:(s&jt)!==0?r.push(i):hr(i)&&((s&yt)!==0&&this.#o.add(i),zt(i));var u=i.first;if(u!==null){i=u;continue}}for(;i!==null;){var f=i.next;if(f!==null){i=f;break}i=i.parent}}}#p(e){for(var r=0;r<e.length;r+=1)hs(e[r],this.#s,this.#o)}capture(e,r){r!==ce&&!this.previous.has(e)&&this.previous.set(e,r),(e.f&ft)===0&&(this.current.set(e,e.v),Be?.set(e,e.v))}activate(){V=this}deactivate(){V=null,Be=null}flush(){try{if(an=!0,V=this,!this.#u()){for(let e of this.#s)this.#o.delete(e),re(e,ve),this.schedule(e);for(let e of this.#o)re(e,je),this.schedule(e)}this.#f()}finally{Ui=0,wn=null,Rt=null,Ar=null,an=!1,V=null,Be=null,dt.clear()}}discard(){for(let e of this.#t)e(this);this.#t.clear()}#h(){if(Xt.size>1){this.previous.clear();var e=V,r=Be,n=!0;for(let i of Xt){if(i===this){n=!1;continue}let s=[];for(let[l,c]of this.current){if(i.current.has(l))if(n&&c!==i.current.get(l))i.current.set(l,c);else continue;s.push(l)}if(s.length===0)continue;let a=[...i.current.keys()].filter(l=>!this.current.has(l));if(a.length>0){i.activate();let l=new Set,c=new Map;for(let u of s)Ss(u,a,l,c);if(i.#n.length>0){i.apply();for(let u of i.#n)i.#d(u,[],[])}i.deactivate()}}V=e,Be=r}this.#a.clear(),Xt.delete(this)}increment(e){this.#r+=1,e&&(this.#l+=1)}decrement(e,r){this.#r-=1,e&&(this.#l-=1),!(this.#c||r)&&(this.#c=!0,it(()=>{this.#c=!1,this.flush()}))}oncommit(e){this.#e.add(e)}ondiscard(e){this.#t.add(e)}settled(){return(this.#i??=Zi()).promise}static ensure(){if(V===null){let e=V=new t;an||(Xt.add(V),tr||it(()=>{V===e&&e.flush()}))}return V}apply(){}schedule(e){if(wn=e,e.b?.is_pending&&(e.f&(jt|Sr|xn))!==0&&(e.f&wt)===0){e.b.defer_effect(e);return}for(var r=e;r.parent!==null;){r=r.parent;var n=r.f;if(Rt!==null&&r===M&&(N===null||(N.f&he)===0))return;if((n&(pt|Ze))!==0){if((n&ue)===0)return;r.f^=ue}}this.#n.push(r)}};function q(t){var e=tr;tr=!0;try{for(var r;;){if(Sa(),V===null)return r;V.flush()}}finally{tr=e}}function Ml(){try{ba()}catch(t){ut(t,wn)}}var tt=null;function Ki(t){var e=t.length;if(e!==0){for(var r=0;r<e;){var n=t[r++];if((n.f&(Ve|Je))===0&&hr(n)&&(tt=new Set,zt(n),n.deps===null&&n.first===null&&n.nodes===null&&n.teardown===null&&n.ac===null&&qs(n),tt?.size>0)){dt.clear();for(let i of tt){if((i.f&(Ve|Je))!==0)continue;let s=[i],a=i.parent;for(;a!==null;)tt.has(a)&&(tt.delete(a),s.push(a)),a=a.parent;for(let l=s.length-1;l>=0;l--){let c=s[l];(c.f&(Ve|Je))===0&&zt(c)}}tt.clear()}}tt=null}}function Ss(t,e,r,n){if(!r.has(t)&&(r.add(t),t.reactions!==null))for(let i of t.reactions){let s=i.f;(s&he)!==0?Ss(i,e,r,n):(s&(Cn|yt))!==0&&(s&ve)===0&&$s(i,e,n)&&(re(i,ve),Bn(i))}}function $s(t,e,r){let n=r.get(t);if(n!==void 0)return n;if(t.deps!==null)for(let i of t.deps){if(Vt.call(e,i))return!0;if((i.f&he)!==0&&$s(i,e,r))return r.set(i,!0),!0}return r.set(t,!1),!1}function Bn(t){V.schedule(t)}function Ms(t,e){if(!((t.f&Ze)!==0&&(t.f&ue)!==0)){(t.f&ve)!==0?e.d.push(t):(t.f&je)!==0&&e.m.push(t),re(t,ue);for(var r=t.first;r!==null;)Ms(r,e),r=r.next}}var _n=new Set,dt=new Map,Fs=!1;function ur(t,e){var r={f:0,v:t,reactions:null,equals:Xi,rv:0,wv:0};return r}function F(t,e){let r=ur(t);return Ls(r),r}function Ns(t,e=!1,r=!0){let n=ur(t);return e||(n.equals=fa),n}function _(t,e,r=!1){N!==null&&(!Pe||(N.f&$i)!==0)&&ns()&&(N.f&(he|yt|Cn|$i))!==0&&(Ie===null||!Vt.call(Ie,t))&&Ea();let n=r?nt(e):e;return Tr(t,n,Ar)}function Tr(t,e,r=null){if(!t.equals(e)){var n=t.v;bt?dt.set(t,e):dt.set(t,n),t.v=e;var i=mt.ensure();if(i.capture(t,n),(t.f&he)!==0){let s=t;(t.f&ve)!==0&&In(s),Mn(s)}t.wv=Rs(),Is(t,ve,r),M!==null&&(M.f&ue)!==0&&(M.f&(Ze|pt))===0&&(Fe===null?Nl([t]):Fe.push(t)),!i.is_fork&&_n.size>0&&!Fs&&Fl()}return e}function Fl(){Fs=!1;for(let t of _n)(t.f&ue)!==0&&re(t,je),hr(t)&&zt(t);_n.clear()}function rr(t){_(t,t.v+1)}function Is(t,e,r){var n=t.reactions;if(n!==null)for(var i=n.length,s=0;s<i;s++){var a=n[s],l=a.f,c=(l&ve)===0;if(c&&re(a,e),(l&he)!==0){var u=a;Be?.delete(u),(l&$t)===0&&(l&Ne&&(a.f|=$t),Is(u,je,r))}else if(c){var f=a;(l&yt)!==0&&tt!==null&&tt.add(f),r!==null?r.push(f):Bn(f)}}}var xr=!1,bt=!1;function zi(t){bt=t}var N=null,Pe=!1;function Le(t){N=t}var M=null;function Xe(t){M=t}var Ie=null;function Ls(t){N!==null&&(Ie===null?Ie=[t]:Ie.push(t))}var _e=null,xe=0,Fe=null;function Nl(t){Fe=t}var Ds=1,Ct=0,St=Ct;function Hi(t){St=t}function Rs(){return++Ds}function hr(t){var e=t.f;if((e&ve)!==0)return!0;if(e&he&&(t.f&=~$t),(e&je)!==0){for(var r=t.deps,n=r.length,i=0;i<n;i++){var s=r[i];if(hr(s)&&bs(s),s.wv>t.wv)return!0}(e&Ne)!==0&&Be===null&&re(t,ue)}return!1}function Bs(t,e,r=!0){var n=t.reactions;if(n!==null&&!(Ie!==null&&Vt.call(Ie,t)))for(var i=0;i<n.length;i++){var s=n[i];(s.f&he)!==0?Bs(s,e,!1):e===s&&(r?re(s,ve):(s.f&ue)!==0&&re(s,je),Bn(s))}}function Ps(t){var e=_e,r=xe,n=Fe,i=N,s=Ie,a=Ee,l=Pe,c=St,u=t.f;_e=null,xe=0,Fe=null,N=(u&(Ze|pt))===0?t:null,Ie=null,Ut(t.ctx),Pe=!1,St=++Ct,t.ac!==null&&(Mr(()=>{t.ac.abort(rt)}),t.ac=null);try{t.f|=un;var f=t.fn,v=f();t.f|=wt;var d=t.deps,b=V?.is_fork;if(_e!==null){var g;if(b||sr(t,xe),d!==null&&xe>0)for(d.length=xe+_e.length,g=0;g<_e.length;g++)d[xe+g]=_e[g];else t.deps=d=_e;if(Pn()&&(t.f&Ne)!==0)for(g=xe;g<d.length;g++)(d[g].reactions??=[]).push(t)}else!b&&d!==null&&xe<d.length&&(sr(t,xe),d.length=xe);if(ns()&&Fe!==null&&!Pe&&d!==null&&(t.f&(he|je|ve))===0)for(g=0;g<Fe.length;g++)Bs(Fe[g],t);if(i!==null&&i!==t){if(Ct++,i.deps!==null)for(let E=0;E<r;E+=1)i.deps[E].rv=Ct;if(e!==null)for(let E of e)E.rv=Ct;Fe!==null&&(n===null?n=Fe:n.push(...Fe))}return(t.f&ft)!==0&&(t.f^=ft),v}catch(E){return cs(E)}finally{t.f^=un,_e=e,xe=r,Fe=n,N=i,Ie=s,Ut(a),Pe=l,St=c}}function Il(t,e){let r=e.reactions;if(r!==null){var n=ra.call(r,t);if(n!==-1){var i=r.length-1;i===0?r=e.reactions=null:(r[n]=r[i],r.pop())}}if(r===null&&(e.f&he)!==0&&(_e===null||!Vt.call(_e,e))){var s=e;(s.f&Ne)!==0&&(s.f^=Ne,s.f&=~$t),Mn(s),rl(s),sr(s,0)}}function sr(t,e){var r=t.deps;if(r!==null)for(var n=e;n<r.length;n++)Il(t,r[n])}function zt(t){var e=t.f;if((e&Ve)===0){re(t,ue);var r=M,n=xr;M=t,xr=!0;try{(e&(yt|xn))!==0?Vl(t):jn(t),zs(t);var i=Ps(t);t.teardown=typeof i=="function"?i:null,t.wv=Ds;var s}finally{xr=n,M=r}}}async function Ot(){await Promise.resolve(),q()}function o(t){var e=t.f,r=(e&he)!==0;if(N!==null&&!Pe){var n=M!==null&&(M.f&Ve)!==0;if(!n&&(Ie===null||!Vt.call(Ie,t))){var i=N.deps;if((N.f&un)!==0)t.rv<Ct&&(t.rv=Ct,_e===null&&i!==null&&i[xe]===t?xe++:_e===null?_e=[t]:_e.push(t));else{(N.deps??=[]).push(t);var s=t.reactions;s===null?t.reactions=[N]:Vt.call(s,N)||s.push(N)}}}if(bt&&dt.has(t))return dt.get(t);if(r){var a=t;if(bt){var l=a.v;return((a.f&ue)===0&&a.reactions!==null||js(a))&&(l=In(a)),dt.set(a,l),l}var c=(a.f&Ne)===0&&!Pe&&N!==null&&(xr||(N.f&Ne)!==0),u=(a.f&wt)===0;hr(a)&&(c&&(a.f|=Ne),bs(a)),c&&!u&&(ys(a),Vs(a))}if(Be?.has(t))return Be.get(t);if((t.f&ft)!==0)throw t.v;return t.v}function Vs(t){if(t.f|=Ne,t.deps!==null)for(let e of t.deps)(e.reactions??=[]).push(t),(e.f&he)!==0&&(e.f&Ne)===0&&(ys(e),Vs(e))}function js(t){if(t.v===ce)return!0;if(t.deps===null)return!1;for(let e of t.deps)if(dt.has(e)||(e.f&he)!==0&&js(e))return!0;return!1}function fr(t){var e=Pe;try{return Pe=!0,t()}finally{Pe=e}}function Ll(t){M===null&&(N===null&&ma(),ga()),bt&&va()}function Dl(t,e){var r=e.last;r===null?e.last=e.first=t:(r.next=t,t.prev=r,e.last=t)}function Ke(t,e){var r=M;r!==null&&(r.f&Je)!==0&&(t|=Je);var n={ctx:Ee,deps:null,nodes:null,f:t|ve|Ne,first:null,fn:e,last:null,next:null,parent:r,b:r&&r.b,prev:null,teardown:null,wv:0,ac:null},i=n;if((t&jt)!==0)Rt!==null?Rt.push(n):mt.ensure().schedule(n);else if(e!==null){try{zt(n)}catch(a){throw de(n),a}i.deps===null&&i.teardown===null&&i.nodes===null&&i.first===i.last&&(i.f&Nt)===0&&(i=i.first,(t&yt)!==0&&(t&vt)!==0&&i!==null&&(i.f|=vt))}if(i!==null&&(i.parent=r,r!==null&&Dl(i,r),N!==null&&(N.f&he)!==0&&(t&pt)===0)){var s=N;(s.effects??=[]).push(i)}return n}function Pn(){return N!==null&&!Pe}function Dr(t){let e=Ke(Sr,null);return re(e,ue),e.teardown=t,e}function Ce(t){Ll();var e=M.f,r=!N&&(e&Ze)!==0&&(e&wt)===0;if(r){var n=Ee;(n.e??=[]).push(t)}else return Us(t)}function Us(t){return Ke(jt|ca,t)}function Rl(t){mt.ensure();let e=Ke(pt|Nt,t);return()=>{de(e)}}function Bl(t){mt.ensure();let e=Ke(pt|Nt,t);return(r={})=>new Promise(n=>{r.outro?nr(e,()=>{de(e),n(void 0)}):(de(e),n(void 0))})}function Vn(t){return Ke(jt,t)}function Pl(t){return Ke(Cn|Nt,t)}function Rr(t,e=0){return Ke(Sr|e,t)}function pe(t,e=[],r=[],n=[]){ws(n,e,r,i=>{Ke(Sr,()=>t(...i.map(o)))})}function dr(t,e=0){var r=Ke(yt|e,t);return r}function Ks(t,e=0){var r=Ke(xn|e,t);return r}function Re(t){return Ke(Ze|Nt,t)}function zs(t){var e=t.teardown;if(e!==null){let r=bt,n=N;zi(!0),Le(null);try{e.call(null)}finally{zi(r),Le(n)}}}function jn(t,e=!1){var r=t.first;for(t.first=t.last=null;r!==null;){let i=r.ac;i!==null&&Mr(()=>{i.abort(rt)});var n=r.next;(r.f&pt)!==0?r.parent=null:de(r,e),r=n}}function Vl(t){for(var e=t.first;e!==null;){var r=e.next;(e.f&Ze)===0&&de(e),e=r}}function de(t,e=!0){var r=!1;(e||(t.f&la)!==0)&&t.nodes!==null&&t.nodes.end!==null&&(Hs(t.nodes.start,t.nodes.end),r=!0),re(t,cn),jn(t,e&&!r),sr(t,0);var n=t.nodes&&t.nodes.t;if(n!==null)for(let s of n)s.stop();zs(t),t.f^=cn,t.f|=Ve;var i=t.parent;i!==null&&i.first!==null&&qs(t),t.next=t.prev=t.teardown=t.ctx=t.deps=t.fn=t.nodes=t.ac=null}function Hs(t,e){for(;t!==null;){var r=t===e?null:Qe(t);t.remove(),t=r}}function qs(t){var e=t.parent,r=t.prev,n=t.next;r!==null&&(r.next=n),n!==null&&(n.prev=r),e!==null&&(e.first===t&&(e.first=n),e.last===t&&(e.last=r))}function nr(t,e,r=!0){var n=[];Ys(t,n,!0);var i=()=>{r&&de(t),e&&e()},s=n.length;if(s>0){var a=()=>--s||i();for(var l of n)l.out(a)}else i()}function Ys(t,e,r){if((t.f&Je)===0){t.f^=Je;var n=t.nodes&&t.nodes.t;if(n!==null)for(let l of n)(l.is_global||r)&&e.push(l);for(var i=t.first;i!==null;){var s=i.next,a=(i.f&vt)!==0||(i.f&Ze)!==0&&(t.f&yt)!==0;Ys(i,e,a?r:!1),i=s}}}function jl(t){Ws(t,!0)}function Ws(t,e){if((t.f&Je)!==0){t.f^=Je,(t.f&ue)===0&&(re(t,ve),mt.ensure().schedule(t));for(var r=t.first;r!==null;){var n=r.next,i=(r.f&vt)!==0||(r.f&Ze)!==0;Ws(r,i?e:!1),r=n}var s=t.nodes&&t.nodes.t;if(s!==null)for(let a of s)(a.is_global||e)&&a.in()}}function Gs(t,e){if(t.nodes)for(var r=t.nodes.start,n=t.nodes.end;r!==null;){var i=r===n?null:Qe(r);e.append(r),r=i}}function qi(t){let e={get:r=>er(e.store)[r],set:(r,n)=>{typeof r=="string"?Object.assign(er(e.store),{[r]:n}):Object.assign(er(e.store),r),e.store.set(er(e.store))},store:Tl(t)};return e}globalThis.$altcha=globalThis.$altcha||{algorithms:new Map,defaults:qi({}),i18n:qi({}),instances:new Set,plugins:new Set};var Ul={ariaLinkLabel:"Altcha (official website)",cancel:"Cancel",enterCode:"Enter code",enterCodeAria:"Enter code you hear. Press Space to play audio.",enterCodeFromImage:"To proceed, please enter the code from the image below.",error:"Verification failed. Try again later.",expired:"Verification expired. Try again.",footer:'Protected by <a href="https://altcha.org/" tabindex="-1" target="_blank" aria-label="Altcha (official website)">ALTCHA</a>',getAudioChallenge:"Get an audio challenge",label:"I'm not a robot",loading:"Loading...",reload:"Reload",verify:"Verify",verificationRequired:"Verification required!",verified:"Verified",verifying:"Verifying...",waitAlert:"Verifying... please wait."};"$altcha"in globalThis&&globalThis.$altcha.i18n.set("en",Ul);var Kl="5";typeof window<"u"&&((window.__svelte??={}).v??=new Set).add(Kl);var zl=Z('<div class="altcha-checkbox"><input/> <svg aria-hidden="true" width="12" height="9" viewBox="0 0 12 9"><polyline points="1 5 4 8 11 1"></polyline></svg> <div class="altcha-spinner altcha-checkbox-spinner" aria-hidden="true"></div></div>');function Js(t,e){st(e,!0);let r=J(e,"loading"),n=Lr(e,["$$slots","$$events","$$legacy","$$host","loading"]),i;function s(){i?.click()}var a={get loading(){return r()},set loading(f){r(f),q()}},l=zl(),c=Q(l);Ir(c,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),gt(c,f=>i=f,()=>i);var u=G(c,2);return Tn(2),W(l),pe(()=>U(l,"data-loading",r())),Fr("click",u,s),L(t,l),ot(a)}Nr(["click"]);_t(Js,{loading:{}},[],[],{mode:"open"});var Hl=Z('<div class="altcha-checkbox-native"><input/> <div class="altcha-spinner altcha-checkbox-native-spinner"></div></div>');function Zs(t,e){st(e,!0);let r=J(e,"loading"),n=Lr(e,["$$slots","$$events","$$legacy","$$host","loading"]);var i={get loading(){return r()},set loading(l){r(l),q()}},s=Hl(),a=Q(s);return Ir(a,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),Tn(2),W(s),pe(()=>U(s,"data-loading",r())),L(t,s),ot(i)}_t(Zs,{loading:{}},[],[],{mode:"open"});var ql=Z('<div><a target="_blank" class="altcha-logo" aria-hidden="true" tabindex="-1"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.33955 16.4279C5.88954 20.6586 12.1971 21.2105 16.4279 17.6604C18.4699 15.947 19.6548 13.5911 19.9352 11.1365L17.9886 10.4279C17.8738 12.5624 16.909 14.6459 15.1423 16.1284C11.7577 18.9684 6.71167 18.5269 3.87164 15.1423C1.03163 11.7577 1.4731 6.71166 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577C16.9767 5.86872 17.5322 7.02798 17.804 8.2324L19.9522 9.01429C19.7622 7.07737 19.0059 5.17558 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956C-0.658625 5.88958 -1.21046 12.1971 2.33955 16.4279Z" fill="currentColor"></path><path d="M3.57212 2.33956C1.65755 3.94607 0.496389 6.11731 0.12782 8.40523L2.04639 9.13961C2.26047 7.15832 3.21057 5.25375 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577L13.8302 6.78606L19.9633 9.13364C19.7929 7.15555 19.0335 5.20847 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956Z" fill="currentColor"></path><path d="M7 10H5C5 12.7614 7.23858 15 10 15C12.7614 15 15 12.7614 15 10H13C13 11.6569 11.6569 13 10 13C8.3431 13 7 11.6569 7 10Z" fill="currentColor"></path></svg></a></div>');function Un(t,e){st(e,!0);let r=J(e,"strings"),n="https://altcha.org";var i={get strings(){return r()},set strings(l){r(l),q()}},s=ql(),a=Q(s);return U(a,"href",n),W(s),pe(()=>U(a,"aria-label",r().ariaLinkLabel)),L(t,s),ot(i)}_t(Un,{strings:{}},[],[],{mode:"open"});var Yl=Z('<div class="altcha-footer"><p></p> <!></div>');function En(t,e){st(e,!0);let r=J(e,"logo"),n=J(e,"strings");var i={get logo(){return r()},set logo(u){r(u),q()},get strings(){return n()},set strings(u){n(u),q()}},s=Yl(),a=Q(s);Es(a,()=>n().footer,!0),W(a);var l=G(a,2);{var c=u=>{Un(u,{get strings(){return n()}})};ae(l,u=>{r()&&u(c)})}return W(s),L(t,s),ot(i)}_t(En,{logo:{},strings:{}},[],[],{mode:"open"});var Wl=Z('<div class="altcha-switch"><input/>  <div class="altcha-switch-toggle"><div class="altcha-spinner altcha-switch-spinner"></div></div></div>');function Xs(t,e){st(e,!0);let r=J(e,"loading"),n=Lr(e,["$$slots","$$events","$$legacy","$$host","loading"]),i;function s(){i?.click()}var a={get loading(){return r()},set loading(f){r(f),q()}},l=Wl(),c=Q(l);Ir(c,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),gt(c,f=>i=f,()=>i);var u=G(c,2);return W(l),pe(()=>U(l,"data-loading",r())),Fr("click",u,s),L(t,l),ot(a)}Nr(["click"]);_t(Xs,{loading:{}},[],[],{mode:"open"});var fe=(t=>(t.ERROR="error",t.LOADING="loading",t.PLAYING="playing",t.PAUSED="paused",t.READY="ready",t))(fe||{}),P=(t=>(t.CODE="code",t.ERROR="error",t.VERIFIED="verified",t.VERIFYING="verifying",t.UNVERIFIED="unverified",t.EXPIRED="expired",t))(P||{}),Gl=Z('<div class="altcha-code-challenge-title"> </div>'),Jl=Z('<div class="altcha-spinner"></div>'),Zl=Fn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>'),Xl=Fn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 7C15 6.44772 15.4477 6 16 6C16.5523 6 17 6.44772 17 7V17C17 17.5523 16.5523 18 16 18C15.4477 18 15 17.5523 15 17V7ZM7 7C7 6.44772 7.44772 6 8 6C8.55228 6 9 6.44772 9 7V17C9 17.5523 8.55228 18 8 18C7.44772 18 7 17.5523 7 17V7Z"></path></svg>'),Ql=Fn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 12H7C8.10457 12 9 12.8954 9 14V19C9 20.1046 8.10457 21 7 21H4C2.89543 21 2 20.1046 2 19V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12V19C22 20.1046 21.1046 21 20 21H17C15.8954 21 15 20.1046 15 19V14C15 12.8954 15.8954 12 17 12H20C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12Z"></path></svg>'),ec=Z('<button type="button" class="altcha-button altcha-button-secondary"><!></button>'),tc=Z('<audio hidden="" autoplay=""></audio>'),rc=Z('<div class="altcha-code-challenge"><form data-code-challenge="true"><!> <div class="altcha-code-challenge-text"> </div> <img class="altcha-code-challenge-image" alt=""/> <div class="altcha-code-challenge-row"><input type="text" class="altcha-input" autocomplete="off" name="" required=""/> <!> <button type="button" class="altcha-button altcha-button-secondary"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 9.25022 5.38734 6.82447 7.50024 5.38451L7.5 8H9.5V2L3.5 2V4L5.99918 3.99989C3.57075 5.82434 2 8.72873 2 12Z"></path></svg></button></div> <div class="altcha-code-challenge-buttons"><button type="submit" class="altcha-button"> </button> <button type="button" class="altcha-button altcha-button-secondary"> </button></div></form> <!></div>');function Qs(t,e){st(e,!0);let r=J(e,"audioUrl"),n=J(e,"codeChallenge"),i=J(e,"config"),s=J(e,"imageUrl"),a=J(e,"onCancel"),l=J(e,"onReload"),c=J(e,"onSubmit"),u=J(e,"strings"),f=F(void 0),v=F(void 0),d=F(void 0),b=F(!1),g=F(""),E=F(!1);Rn(()=>(i().disableAutoFocus||Ot().then(()=>{o(d)?.focus()}),()=>{o(v)&&(o(v).pause(),_(v,void 0))}));function O(){_(f,fe.PAUSED,!0)}function $(y){_(f,fe.ERROR,!0)}function se(){_(f,fe.READY,!0)}function ne(){_(f,fe.LOADING,!0)}function oe(){_(f,fe.PLAYING,!0)}function K(){_(f,fe.PAUSED,!0)}function ze(y){y.code==="Space"?(y.preventDefault(),y.stopPropagation(),I()):y.code==="Escape"&&(y.preventDefault(),y.stopPropagation(),a()?.())}function A(y){y.preventDefault(),y.stopPropagation(),c()?.(o(g))}function I(){o(v)?o(f)===fe.LOADING||(o(v).paused?(r()&&o(v).src!==r()&&(o(v).src=r()),o(v).currentTime=0,o(v).play()):o(v).pause()):(_(E,!0),requestAnimationFrame(()=>{o(v)&&r()&&(o(v).src=r(),o(v).play())}))}var ee={get audioUrl(){return r()},set audioUrl(y){r(y),q()},get codeChallenge(){return n()},set codeChallenge(y){n(y),q()},get config(){return i()},set config(y){i(y),q()},get imageUrl(){return s()},set imageUrl(y){s(y),q()},get onCancel(){return a()},set onCancel(y){a(y),q()},get onReload(){return l()},set onReload(y){l(y),q()},get onSubmit(){return c()},set onSubmit(y){c(y),q()},get strings(){return u()},set strings(y){u(y),q()}},B=rc(),R=Q(B),De=Q(R);{var ge=y=>{var te=Gl(),At=Q(te,!0);W(te),pe(()=>We(At,u().verificationRequired)),L(y,te)};ae(De,y=>{i().codeChallengeDisplay!=="standard"&&y(ge)})}var ye=G(De,2),X=Q(ye,!0);W(ye);var He=G(ye,2),x=G(He,2),z=Q(x);Ln(z),z.disabled=o(b),gt(z,y=>_(d,y),()=>o(d));var ke=G(z,2);{var m=y=>{var te=ec(),At=Q(te);{var qr=we=>{var qe=Jl();L(we,qe)},Wt=we=>{var qe=Zl();L(we,qe)},Yr=we=>{var qe=Xl();L(we,qe)},Wr=we=>{var qe=Ql();L(we,qe)};ae(At,we=>{o(f)===fe.LOADING?we(qr):o(f)===fe.ERROR?we(Wt,1):o(f)===fe.PLAYING?we(Yr,2):we(Wr,-1)})}W(te),pe(()=>{U(te,"title",u().getAudioChallenge),te.disabled=o(f)===fe.LOADING||o(f)===fe.ERROR,U(te,"aria-label",o(f)===fe.LOADING?u().loading:u().getAudioChallenge)}),le("click",te,()=>I(),!0),L(y,te)};ae(ke,y=>{n().audio&&y(m)})}var Et=G(ke,2);W(x);var mr=G(x,2),Se=Q(mr),Hr=Q(Se,!0);W(Se);var kt=G(Se,2),Ht=Q(kt,!0);W(kt),W(mr),W(R);var qt=G(R,2);{var Yt=y=>{var te=tc();gt(te,At=>_(v,At),()=>o(v)),le("error",te,$),le("loadstart",te,ne),le("canplay",te,se),le("pause",te,K),le("playing",te,oe),le("ended",te,O),L(y,te)};ae(qt,y=>{o(E)&&y(Yt)})}return W(B),pe(()=>{We(X,u().enterCodeFromImage),U(He,"src",s()),U(z,"minlength",n().length||1),U(z,"maxlength",n().length),U(z,"placeholder",u().enterCode),U(z,"aria-label",o(f)===fe.LOADING?u().loading:o(f)===fe.PLAYING?"":u().enterCodeAria),U(z,"aria-live",o(f)?"assertive":"polite"),U(z,"aria-busy",o(f)===fe.LOADING),U(Et,"title",u().reload),U(Et,"aria-label",u().reload),U(Se,"aria-label",u().verify),We(Hr,u().verify),U(kt,"aria-label",u().cancel),We(Ht,u().cancel)}),le("submit",R,A,!0),Fr("keydown",z,ze),Al(z,()=>o(g),y=>_(g,y)),le("click",Et,()=>l()?.(),!0),le("click",kt,()=>a()?.(),!0),L(t,B),ot(ee)}Nr(["keydown"]);_t(Qs,{audioUrl:{},codeChallenge:{},config:{},imageUrl:{},onCancel:{},onReload:{},onSubmit:{},strings:{}},[],[],{mode:"open"});var nc=Z('<div class="altcha-popover-backdrop" data-backdrop=""></div>'),ic=Z('<div class="altcha-popover-arrow"></div>'),sc=Z('<div role="button" class="altcha-popover-close">&times;</div>'),oc=Z('<!> <div><!> <!> <div class="altcha-popover-content"><!></div></div>',1);function kn(t,e){st(e,!0);let r=J(e,"anchor"),n=J(e,"children"),i=J(e,"display",7,"standard"),s=J(e,"backdrop",7,!1),a=J(e,"onClickOutside"),l=J(e,"onClickOutsideDelay",7,600),c=J(e,"onClose"),u=J(e,"placement",7,"auto"),f=J(e,"updateUISignal"),v=J(e,"variant",7,"neutral"),d=Lr(e,["$$slots","$$events","$$legacy","$$host","anchor","children","display","backdrop","onClickOutside","onClickOutsideDelay","onClose","placement","updateUISignal","variant"]),b=F(void 0),g=F(void 0),E=F(!1),O=F(0);Ce(()=>{u()!=="auto"&&_(E,u()==="top")}),Ce(()=>{f()&&K()}),Rn(()=>{let x=i()==="bottomsheet"||i()==="overlay";return x&&(o(g)&&document.body.append(o(g)),o(b)&&document.body.append(o(b))),K(),Ot().then(()=>{_(O,Date.now(),!0)}),()=>{x&&(o(g)&&document.body.removeChild(o(g)),o(b)&&document.body.removeChild(o(b)))}});function $(){c()?.()}function se(x){let z=x.target;!o(b)?.contains(z)&&(!l()||o(O)+l()<Date.now())&&a()?.()}function ne(){K()}function oe(){K()}function K(){if(r()&&u()==="auto"&&o(b)){let x=r().getBoundingClientRect(),ke=document.documentElement.clientHeight-(x.top+x.height)<o(b).clientHeight;o(E)!==ke&&_(E,ke)}}var ze={get anchor(){return r()},set anchor(x){r(x),q()},get children(){return n()},set children(x){n(x),q()},get display(){return i()},set display(x="standard"){i(x),q()},get backdrop(){return s()},set backdrop(x=!1){s(x),q()},get onClickOutside(){return a()},set onClickOutside(x){a(x),q()},get onClickOutsideDelay(){return l()},set onClickOutsideDelay(x=600){l(x),q()},get onClose(){return c()},set onClose(x){c(x),q()},get placement(){return u()},set placement(x="auto"){u(x),q()},get updateUISignal(){return f()},set updateUISignal(x){f(x),q()},get variant(){return v()},set variant(x="neutral"){v(x),q()}},A=oc();le("click",Tt,se,!0),le("resize",Tt,ne),le("scroll",Tt,oe);var I=Dt(A);{var ee=x=>{var z=nc();gt(z,ke=>_(g,ke),()=>o(g)),L(x,z)};ae(I,x=>{s()&&x(ee)})}var B=G(I,2);Ir(B,()=>({...d,class:`altcha-popover ${(e.class||"")??""}`,"data-popover":!0,"data-variant":v(),"data-top":o(E),"data-display":i()}));var R=Q(B);{var De=x=>{var z=ic();L(x,z)};ae(R,x=>{i()==="standard"&&x(De)})}var ge=G(R,2);{var ye=x=>{var z=sc();le("click",z,$,!0),L(x,z)};ae(ge,x=>{i()!=="standard"&&x(ye)})}var X=G(ge,2),He=Q(X);return ol(He,()=>n()??ht),W(X),W(B),gt(B,x=>_(b,x),()=>o(b)),L(t,A),ot(ze)}_t(kn,{anchor:{},children:{},display:{},backdrop:{},onClickOutside:{},onClickOutsideDelay:{},onClose:{},placement:{},updateUISignal:{},variant:{}},[],[],{mode:"open"});function ac(t){return Array.from(new Uint8Array(t)).map(e=>e.toString(16).padStart(2,"0")).join("")}function lc(t,e="altcha-css"){if(typeof document<"u"&&document&&!document.getElementById(e)){let r=document.createElement("style");r.id=e,r.textContent=t,document.head.appendChild(r)}}async function eo(t){let{challenge:e,concurrency:r=navigator.hardwareConcurrency,controller:n=new AbortController,createWorker:i,onOutOfMemory:s=d=>d>1?Math.floor(d/2):0,counterMode:a,timeout:l=9e4}=t,c=Math.min(16,Math.max(1,r)),u=[],f=()=>{for(let d of u)d.terminate()};for(let d=0;d<c;d++)u.push(await i(e.parameters.algorithm));let v=null;try{v=await Promise.race(u.map((d,b)=>(n.signal.addEventListener("abort",()=>{d.postMessage({type:"abort"})}),new Promise((g,E)=>{d.addEventListener("error",O=>{E(O)}),d.addEventListener("message",O=>{if(O.data){for(let $ of u)$!==d&&$.postMessage({type:"abort"});if(O.data.error)return E(new Error(O.data.error))}g(O.data)}),d.postMessage({challenge:e,counterMode:a,counterStart:b,counterStep:c,timeout:l,type:"work"})}))))}catch(d){if(d instanceof Error&&!!d?.message?.includes("Out of memory")&&s){f();let g=s(c);if(g)return eo({...t,challenge:e,controller:n,concurrency:g,createWorker:i})}throw d}finally{f()}return n.signal.aborted?null:v||null}var An=class{TAG_CODES={INPUT:1,TEXTAREA:2,SELECT:3,BUTTON:4,A:5,DETAILS:6,SUMMARY:7,IFRAME:8,VIDEO:9,AUDIO:10};maxSamples;sampleInterval;target;focusStartTime=0;focusInteraction=0;focusInteractionTimer=null;lastPointerSample=0;lastTouchSample=0;lastScrollSample=0;pendingPointer=null;pendingTouch=null;focus=[];pointer=[];scroll=[];touch=[];constructor(e={}){let{maxSamples:r=60,sampleInterval:n=50,target:i=window}=e;this.maxSamples=r,this.sampleInterval=n,this.target=i,this.attach()}destroy(){let e={capture:!0};this.target.removeEventListener("focusin",this.onFocus,e),this.target.removeEventListener("keydown",this.onInteraction,e),this.target.removeEventListener("pointerdown",this.onInteraction,e),this.target.removeEventListener("pointermove",this.onPointer,e),this.target.removeEventListener("scroll",this.onScroll,e),this.target.removeEventListener("touchmove",this.onTouchMove,e)}export(){return{focus:this.focus,maxTouchPoints:navigator.maxTouchPoints||0,pointer:this.pointer,scroll:this.scroll,time:Date.now(),touch:this.touch}}attach(){let e={passive:!0,capture:!0};this.target.addEventListener("focusin",this.onFocus,e),this.target.addEventListener("keydown",this.onInteraction,e),this.target.addEventListener("pointerdown",this.onInteraction,e),this.target.addEventListener("pointermove",this.onPointer,e),this.target.addEventListener("scroll",this.onScroll,e),this.target.addEventListener("touchmove",this.onTouchMove,e)}evict(e){e.length>this.maxSamples&&e.splice(0,e.length-this.maxSamples)}onFocus=e=>{if(this.focusInteraction===2)return;let r=e.target;if(!(r instanceof Element))return;let n=performance.now();this.focusStartTime===0&&(this.focusStartTime=n),this.focus.push([Math.round(n-this.focusStartTime),r.tabIndex,this.TAG_CODES[r.tagName]??0,this.focusInteraction?1:0]),this.evict(this.focus)};onInteraction=e=>{this.focusInteraction="keyCode"in e?1:2,this.focusInteractionTimer&&clearTimeout(this.focusInteractionTimer),this.focusInteractionTimer=setTimeout(()=>{this.focusInteraction=0},100)};onPointer=e=>{if(e.pointerType==="touch")return;let r=e.timeStamp||performance.now();this.pendingPointer=[Math.round(e.clientX),Math.round(e.clientY),Math.round(r)],r-this.lastPointerSample>=this.sampleInterval&&(this.pointer.push(this.pendingPointer),this.lastPointerSample=r,this.pendingPointer=null,this.evict(this.pointer))};onScroll=()=>{let e=performance.now();e-this.lastScrollSample<this.sampleInterval||(this.scroll.push([Math.round(window.scrollY),Math.round(e)]),this.lastScrollSample=e,this.evict(this.scroll))};onTouchMove=e=>{let r=e.timeStamp||performance.now(),n=e.touches[0];n&&(this.pendingTouch=[Math.round(n.clientX),Math.round(n.clientY),Math.round(r),Math.round(n.force*1e3)/1e3,Math.round(n.radiusX||0),Math.round(n.radiusY||0)],r-this.lastTouchSample>=this.sampleInterval&&(this.touch.push(this.pendingTouch),this.lastTouchSample=r,this.pendingTouch=null,this.evict(this.touch)))}},cc=Z('<div class="altcha-overlay-backdrop" data-backdrop=""></div>'),uc=Z('<div class="altcha-overlay-content"></div>'),hc=Z('<div role="button" class="altcha-overlay-close">&times;</div> <!>',1),fc=Z('<div class="altcha-floating-arrow"></div>'),dc=Z('<input type="hidden"/>'),pc=Z('<div class="altcha-error">Secure context (HTTPS) required.</div>'),vc=Z('<div class="altcha-error"> </div>'),gc=Z('<div class="altcha-error"> </div>'),mc=Z("<!> <!>",1),bc=Z('<!> <div class="altcha"><!> <div class="altcha-main"><div><div class="altcha-checkbox-wrap"><!> <label><!></label></div> <!></div> <!> <!> <!></div> <!></div>',1);function yc(t,e){st(e,!0);let r=()=>ji(f,"$altchaDefaults",i),n=()=>ji(g,"$altchaI18nStore",i),[i,s]=Sl(),a='input[type="text"]:not([data-no-spamfilter]), textarea:not([data-no-spamfilter])',l='input[type="submit"], button[type="submit"], button:not([type="button"]):not([type="reset"])',c=["ar","fa","he","ur"],{isSecureContext:u}=globalThis,{store:f}=globalThis.$altcha.defaults,v=navigator.hardwareConcurrency||2,d=navigator.deviceMemory||0,b=d&&d<=4?Math.min(4,v):v,g=globalThis.$altcha.i18n.store,E=e.$$host,O=(h,p)=>{Ot().then(()=>{E?.dispatchEvent(new CustomEvent(h,{detail:p}))})},$=null,se=F(nt(new URL(location.origin))),ne=F(!1),oe=F(null),K=F(null),ze=F(null),A=F(nt(P.UNVERIFIED)),I=F(void 0),ee=F(void 0),B=F(null),R=F(void 0),De=F(null),ge=F(null),ye=F(null),X=F(null),He=F(nt([])),x=F(0),z=F(nt({})),ke=F(!0),m=me(()=>({fetch:(h,p)=>fetch(h,p),audioChallengeLanguage:"",auto:"off",barPlacement:"bottom",challenge:"",codeChallenge:null,codeChallengeDisplay:"standard",credentials:null,debug:!1,disableAutoFocus:!1,display:"standard",floatingAnchor:"",floatingOffset:8,floatingPersist:!1,floatingPlacement:"auto",hideFooter:!1,hideLogo:!1,humanInteractionSignature:!0,language:"",mockError:!1,minDuration:500,overlayContent:"",name:"altcha",popoverPlacement:"auto",retryOnOutOfMemoryError:!0,setCookie:null,serverVerificationFields:!1,serverVerificationTimeZone:!1,test:!1,timeout:9e4,type:"checkbox",validationMessage:"",verifyFunction:null,verifyUrl:"",workers:b,...r(),...o(z)})),Et=me(()=>`altcha-checkbox-${e.id||Math.floor(Math.random()*1e12).toString(16)}`),mr=me(()=>yo(o(m).type)),Se=me(()=>o(m).auto),Hr=me(()=>o(A)===P.VERIFYING),kt=me(()=>!o(m).hideFooter),Ht=me(()=>!o(m).hideLogo&&o(m).display!=="bar"),qt=me(()=>wo(n(),[o(m).language,document.documentElement.lang,...navigator.languages])),Yt=me(()=>c.includes(o(qt).language)?"rtl":void 0),y=me(()=>({...o(qt).strings})),te=me(()=>o(oe)?.audio?.match(/^(https?:)?\//)?br(o(oe).audio,o(se),{language:o(m).audioChallengeLanguage||o(qt).language}).toString():o(oe)?.audio),At=me(()=>o(oe)?.image?.match(/^(https?:)?\//)?br(o(oe).image,o(se)):o(oe)?.image);Ce(()=>{Gt({auto:e.auto,challenge:e.challenge,display:e.display,language:e.language,name:e.name,type:e.type,workers:e.workers})}),Ce(()=>{if(e.configuration)try{Gt(JSON.parse(e.configuration))}catch{H("unable to parse the `configuration` attribute (JSON expected)")}}),Ce(()=>{o(ze)!==o(m).display&&yr(o(m).display)}),Ce(()=>{o(ne)&&o(A)===P.VERIFYING&&_(ne,!1)}),Ce(()=>{!o(ne)&&o(A)===P.VERIFIED&&_(ne,!0)}),Ce(()=>{if(!o(ne)){let h=Gr();h&&h.checked&&(h.checked=!1)}}),Ce(()=>{o(A)===P.VERIFIED&&Gr()?.setCustomValidity("")}),Ce(()=>{if(o(Se)==="onload"){let h=setTimeout(()=>{It()},1);return()=>{h&&clearTimeout(h)}}}),Ce(()=>{o(ge)&&H("error:",o(ge))}),Ce(()=>{o(X)&&o(m).setCookie&&Io(o(X),o(m).setCookie)}),Rn(()=>(H("mounted","3.0.9"),E&&globalThis.$altcha.instances.add(E),_(B,o(R)?.closest("form"),!0),o(B)?.addEventListener("reset",bi),o(B)?.addEventListener("submit",yi,{capture:!0}),o(B)?.addEventListener("focusin",mi),qr(),o(m).humanInteractionSignature&&(H("human interaction signature enabled"),$=new An),O("load"),u||H("secure context (HTTPS) required"),()=>{Yr(),E&&globalThis.$altcha.instances.delete(E),o(ye)&&clearTimeout(o(ye)),o(B)?.removeEventListener("reset",bi),o(B)?.removeEventListener("submit",yi,{capture:!0}),o(B)?.removeEventListener("focusin",mi),$?.destroy()}));function qr(){_(He,[...globalThis.$altcha.plugins].map(h=>new h(E)),!0),H("activating plugins",o(He).map(h=>h.constructor.name));for(let h of o(He))h.activate()}async function Wt(h,...p){let w;for(let k of o(He))w=await k[h].call(k,...p);return w}function Yr(){for(let h of o(He))h.destroy()}function Wr(h){let[p,w]=h.salt.split("?"),k={};if(w)try{Object.assign(k,Object.fromEntries(new URLSearchParams(w).entries()))}catch{}let T={codeChallenge:h.codeChallenge,parameters:{algorithm:h.algorithm,cost:1,data:k,expiresAt:k?.expires?parseInt(k.expires,10):void 0,keyLength:h.algorithm==="SHA-512"?64:h.algorithm==="SHA-384"?48:32,nonce:ac(new TextEncoder().encode(h.salt)),keyPrefix:h.challenge,salt:""},signature:h.signature};return Object.defineProperties(T,{_originalSalt:{enumerable:!1,value:h.salt,writable:!1},_version:{enumerable:!1,value:1,writable:!1}}),T}function we(h,p){return{algorithm:h.parameters.algorithm,challenge:h.parameters.keyPrefix,number:p.counter,salt:"_originalSalt"in h?h._originalSalt:h.parameters.nonce,signature:h.signature,took:p.time||0}}async function qe(h){await new Promise(p=>setTimeout(p,h))}async function gi(h=o(m).challenge,p){let w=await Wt("onFetchChallenge",h),k=null;if(w!==void 0)return w;if(typeof h=="string")if(h.startsWith("{")){H("parsing JSON challenge");try{k=JSON.parse(h)}catch{throw new Error("Unable to parse JSON challenge.")}}else{H("fetching challenge from",p?.method||"GET",h),_(se,new URL(h,location.origin),!0);let T=await o(m).fetch(h,{credentials:o(m).credentials||void 0,...p});await _i(T);let C=T.headers.get("x-altcha-config");C&&Mo(C);let j=await T.json();if(j&&"his"in j&&j.his){if(H("requested HIS"),!$)throw new Error("Server requested HIS data but collector is disabled.");return gi(br(j.his.url,o(se)),{body:JSON.stringify({his:$.export()}),headers:{"content-type":"application/json"},method:"POST"})}j&&"hisResult"in j&&j.hisResult&&H("HIS result",j.hisResult),k=j}else if(h&&typeof h=="object")try{k=JSON.parse(JSON.stringify(h))}catch{throw new Error("Unable to parse JSON challenge.")}if(mo(k)&&(k=Wr(k)),!bo(k))throw new Error("Challenge validation failed.");return k}function mo(h){return typeof h=="object"&&"challenge"in h}function bo(h){return!!h&&typeof h=="object"&&"parameters"in h&&!!h.parameters&&typeof h.parameters=="object"&&"algorithm"in h.parameters&&"nonce"in h.parameters&&"salt"in h.parameters&&"keyPrefix"in h.parameters}function Gr(){return document.getElementById(o(Et))}function yo(h){switch(h){case"checkbox":return Js;case"switch":return Xs;default:return Zs}}function wo(h,p){let w=Object.keys(h).map(T=>T.toLowerCase()),k=p.reduce((T,C)=>(C=C.toLowerCase(),T||(h[C]?C:null)||w.find(j=>C.split("-")[0]===j.split("-")[0])||null),null);return h[k||""]||(k="en"),{language:k,strings:h[k]}}function _o(h){switch(h){case"bar":return o(m).barPlacement||"bottom";case"floating":return o(m).floatingPlacement||"auto";default:return}}function Eo(h){return[...o(B)?.querySelectorAll(a)||[]].reduce((w,k)=>{let T=k.name,C=k.value;return T&&C&&(w[T]=/\n/.test(C)?C.replace(new RegExp("(?<!\\r)\\n","g"),`\r
`):C),w},{})}function ko(){try{return Intl.DateTimeFormat().resolvedOptions().timeZone}catch{}}function br(h,p,w){let k=new URL(h,p);if(k.search||(k.search=p.search),w)for(let T in w)w[T]!==void 0&&w[T]!==null&&k.searchParams.set(T,w[T]);return k.toString()}function Ao(h){!o(ne)&&h.currentTarget.checked?(h.preventDefault(),h.currentTarget.checked=!1,o(A)!==P.VERIFYING&&It()):h.currentTarget.checked||(h.preventDefault(),$e())}function xo(h){o(A)===P.VERIFYING?h.currentTarget.setCustomValidity(o(y).waitAlert):o(m).validationMessage&&h.currentTarget.setCustomValidity(o(m).validationMessage)}function Co(){yr(o(m).display),$e()}function Oo(){wr()}function To(h){let p=h.target;o(m).display==="floating"&&p&&!E?.contains(p)&&!p.hasAttribute("data-backdrop")&&!p.closest("[data-popover]")&&o(A)!==P.VERIFIED&&!o(m).floatingPersist&&Jr()}function mi(h){o(Se)==="onfocus"&&o(A)===P.UNVERIFIED&&It()}function bi(){yr(o(m).display),$e()}function yi(h){h.target?.getAttribute("data-code-challenge")!=="true"&&o(Se)==="onsubmit"&&o(A)===P.UNVERIFIED&&(h.preventDefault(),h.stopPropagation(),_(De,h.submitter,!0),Zr(),It().then(w=>{w&&!o(oe)&&Ot().then(()=>{wi(o(De))})}))}function So(h){h.persisted&&(yr(o(m).display),$e())}function $o(){wr()}function Mo(h){try{let p=JSON.parse(h);p&&typeof p=="object"&&Gt({serverVerificationFields:p?.sentinel?.fields,serverVerificationTimeZone:p?.sentinel?.timeZone,verifyUrl:p.verifyurl,...p})}catch(p){H("unable to configure from x-altcha-config header",p)}}function Fo(h=20){if(!o(R))return;let p=o(m).floatingPlacement;if(!o(ee)&&(_(ee,(o(m).floatingAnchor instanceof HTMLElement?o(m).floatingAnchor:o(m).floatingAnchor?document.querySelector(o(m).floatingAnchor):o(B)?.querySelector(l))||o(B),!0),!o(ee))){H("unable to find floating anchor element");return}let w=parseInt(o(m).floatingOffset,10)||12,k=o(ee).getBoundingClientRect(),T=o(R).getBoundingClientRect(),C=document.documentElement.clientHeight,j=document.documentElement.clientWidth,Ae=!p||p==="auto"?k.bottom+T.height+w+h>C:p==="top",Y=Math.max(h,Math.min(j-h-T.width,k.left+k.width/2-T.width/2));if(o(R).style.setProperty("--altcha-floating-left",`${Y}px`),o(R).style.setProperty("--altcha-floating-top",Ae?`${k.top-(T.height+w)}px`:`${k.bottom+w}px`),o(R).setAttribute("data-floating-position",Ae?"top":"bottom"),o(I)){let ie=o(I).getBoundingClientRect();o(I).style.left=k.left-Y+k.width/2-ie.width/2+"px"}}async function No(h,p){let w=await Wt("onRequestServerVerification",h,p);if(w!==void 0)return w;if(H("requesting server verification from",o(m).verifyUrl),!o(m).verifyUrl)throw new Error("Parameter verifyUrl must be set for server verification.");let k=await o(m).fetch(br(o(m).verifyUrl,o(se)),{body:JSON.stringify({code:p,fields:o(m).serverVerificationFields?Eo():void 0,payload:h,timeZone:o(m).serverVerificationTimeZone?ko():void 0}),credentials:o(m).credentials||void 0,headers:{"Content-Type":"application/json"},method:"POST"});await _i(k);let T=await k.json();return T&&typeof T=="object"&&"payload"in T&&T.payload&&O("serververification",T),T}function wi(h){o(B)&&"requestSubmit"in o(B)?o(B).requestSubmit(h):o(B)?.reportValidity()&&(h?h.click():o(B).submit())}function Io(h,p={}){let{domain:w,name:k=o(m).name,maxAge:T,path:C,sameSite:j,secure:Ae}=p,Y=`${encodeURIComponent(k)}=${encodeURIComponent(h)}`;w&&(Y+=`; Domain=${w}`),T!=null&&(Y+=`; Max-Age=${T}`),C&&(Y+=`; Path=${C}`),j&&(Y+=`; SameSite=${j}`),Ae&&(Y+="; Secure"),document.cookie=Y}function yr(h){switch(h){case"bar":case"floating":case"overlay":Jr(),(!o(Se)||o(Se)==="off")&&(o(z).auto="onsubmit");break;case"standard":Zr()}o(ze)!==h&&_(ze,h,!0)}function Lo(h){o(ye)&&clearTimeout(o(ye));let p=()=>{o(A)!==P.UNVERIFIED?(_(ne,!1),Me(P.EXPIRED)):$e(),O("expired")},w=h*1e3-Date.now();w>=1?_(ye,setTimeout(p,w),!0):p()}async function _i(h){if(h.status>=400){if(h.headers.get("content-type")?.includes("/json")){let w;try{w=await h.json()}catch{}if(w&&"error"in w)throw new Error(`Server responded with ${h.status} - ${w.error}`)}throw new Error(`Server responded with ${h.status}.`)}let p=h.headers.get("content-type");if(!p||!p.includes("/json"))throw new Error(`Server responded with invalid content-type. Expected application/json, received ${p}.`)}async function Ei(h){if(!o(X)){Me(P.ERROR,"Cannot verify code challenge without PoW payload.");return}Me(P.VERIFYING);let p=null;if(o(m).verifyUrl)p=await No(o(X),h);else if(o(m).verifyFunction)p=await o(m).verifyFunction(o(X),h);else{Me(P.ERROR,"Parameter verifyUrl is required for code challenge verification.");return}p?.payload&&(_(X,p.payload,!0),H("server payload",o(X))),p?.verified===!0?(H("verified"),Me(P.VERIFIED),O("verified",{payload:o(X)}),o(Se)==="onsubmit"&&Ot().then(()=>{wi(o(De))})):Me(P.ERROR,p?.reason||"Verification failed."),o(m).disableAutoFocus||Gr()?.focus()}function Gt(h){Object.assign(o(z),{...Object.fromEntries(Object.entries(h).filter(([p,w])=>w!==void 0))})}function Do(){return{...o(m)}}function Ro(){return o(A)}function Jr(){_(ke,!1)}function H(...h){(o(m).debug||h.some(p=>p instanceof Error))&&console[h[0]instanceof Error?"error":"log"]("ALTCHA",`[name=${o(m).name}]`,...h)}function $e(h=P.UNVERIFIED,p=null){_(ne,!1),_(ge,p,!0),_(X,null),o(K)&&o(K).abort(),o(ye)&&(clearTimeout(o(ye)),_(ye,null)),Me(h)}function Me(h,p=null){_(A,h,!0),_(ge,p,!0),O("statechange",{payload:o(X),state:o(A)})}function Zr(){_(ke,!0),Ot().then(()=>{wr()})}function wr(){if(o(m).display==="floating")return Fo();_(x,o(x)+1)}async function It(h={}){let{concurrency:p=Math.max(1,o(m).workers),controller:w=new AbortController,minDuration:k=o(m).minDuration}=h,T=performance.now(),C=null,j=null,Ae=!1,Y=await Wt("onVerify",h);if(Y!==void 0)return Y;$e(P.VERIFYING),_(K,w,!0);try{if(!u)throw new Error("Secure context (HTTPS) required.");if(o(m).mockError)throw new Error("Mock error.");if(o(m).test)return H("running test mode with null challenge"),await qe(Math.max(0,k-(performance.now()-T))),o(K)?.signal.aborted?($e(),null):(_(X,btoa(JSON.stringify({challenge:null,solution:null,test:!0})),!0),H("verified"),Me(P.VERIFIED),O("verified",{payload:o(X)}),{payload:o(X)});if(C=await gi(),!C)throw new Error("Failed to fetch challenge.");H("challenge",C),"configuration"in C&&(H("re-configuring from challenge",C.configuration),Gt(C.configuration)),C.parameters.expiresAt&&Lo(C.parameters.expiresAt),Ae="_version"in C&&C._version===1;let ie=globalThis.$altcha.algorithms.get(C.parameters.algorithm);if(!ie)throw new Error(`Unsupported algorithm ${C.parameters.algorithm}.`);if(j=await eo({challenge:C,concurrency:p,controller:w,createWorker:ie,counterMode:Ae?"string":"uint32",onOutOfMemory:lt=>{if(H("out of memory error received"),O("outofmemory"),o(m).retryOnOutOfMemoryError&&lt>1){let ct=Math.floor(lt/2);return H(`retrying with ${ct} workers...`),ct}},timeout:o(m).timeout}),o(K)?.signal.aborted)return $e(),null;if(!j)throw new Error("Failed to find solution.");H("solution",j),await qe(Math.max(0,k-(performance.now()-T))),_(oe,C.codeChallenge||o(m).codeChallenge||null,!0),Ae?_(X,btoa(JSON.stringify(we(C,j))),!0):_(X,btoa(JSON.stringify({challenge:{parameters:C.parameters,signature:C.signature},solution:j})),!0),o(oe)?(H("requesting code verification"),Me(P.CODE),O("codechallenge",{codeChallenge:o(oe)})):o(m).verifyUrl?await Ei():(H("verified"),Me(P.VERIFIED),O("verified",{payload:o(X)}))}catch(ie){return H("verification failed",ie),Me(P.ERROR,String(ie)),null}finally{_(K,null)}return{challenge:C,payload:o(X),solution:j}}var Bo={configure:Gt,getConfiguration:Do,getState:Ro,hide:Jr,log:H,reset:$e,setState:Me,show:Zr,updateUI:wr,verify:It},ki=bc();le("scroll",hn,Oo),le("click",hn,To),le("pageshow",Tt,So),le("resize",Tt,$o);var Ai=Dt(ki);{var Po=h=>{var p=cc();L(h,p)};ae(Ai,h=>{o(m).display==="overlay"&&o(ke)&&h(Po)})}var Ye=G(Ai,2),xi=Q(Ye);{var Vo=h=>{var p=hc(),w=Dt(p),k=G(w,2);{var T=C=>{var j=uc();Es(j,()=>document.querySelector(o(m).overlayContent)?.innerHTML,!0),W(j),L(C,j)};ae(k,C=>{o(m).overlayContent&&C(T)})}le("click",w,Co,!0),L(h,p)};ae(xi,h=>{o(m).display==="overlay"&&o(ke)&&h(Vo)})}var Xr=G(xi,2),Qr=Q(Xr),en=Q(Qr),Ci=Q(en);{let h=me(()=>o(m).display==="standard"&&o(Se)!=="onsubmit"||o(A)===P.VERIFYING);al(Ci,()=>o(mr),(p,w)=>{w(p,{get id(){return o(Et)},name:"",get required(){return o(h)},get loading(){return o(Hr)},get checked(){return o(ne)},onchange:Ao,oninvalid:xo})})}var tn=G(Ci,2),jo=Q(tn);{var Uo=h=>{var p=_r();pe(()=>We(p,o(y).verificationRequired)),L(h,p)},Ko=h=>{var p=_r();pe(()=>We(p,o(y).verifying)),L(h,p)},zo=h=>{var p=_r();pe(()=>We(p,o(y).verified)),L(h,p)},Ho=h=>{var p=_r();pe(()=>We(p,o(y).label)),L(h,p)};ae(jo,h=>{o(A)===P.CODE&&o(oe)?h(Uo):o(A)===P.VERIFYING?h(Ko,1):o(A)===P.VERIFIED?h(zo,2):h(Ho,-1)})}W(tn),W(en);var qo=G(en,2);{var Yo=h=>{Un(h,{get strings(){return o(y)}})};ae(qo,h=>{o(Ht)&&h(Yo)})}W(Qr);var Oi=G(Qr,2);{var Wo=h=>{{let p=me(()=>o(m).display==="bar"&&o(Ht));En(h,{get logo(){return o(p)},get strings(){return o(y)}})}};ae(Oi,h=>{o(kt)&&h(Wo)})}var Ti=G(Oi,2);{var Go=h=>{var p=fc();gt(p,w=>_(I,w),()=>o(I)),L(h,p)};ae(Ti,h=>{o(m).display==="floating"&&h(Go)})}var Jo=G(Ti,2);{var Zo=h=>{var p=dc();Ln(p),pe(()=>{U(p,"name",o(m).name),_l(p,o(X))}),L(h,p)};ae(Jo,h=>{o(m).setCookie||h(Zo)})}W(Xr);var Xo=G(Xr,2);{var Qo=h=>{kn(h,{get anchor(){return o(R)},onClickOutside:()=>{u&&$e()},get placement(){return o(m).popoverPlacement},role:"alert",variant:"error",get dir(){return o(Yt)},get updateUISignal(){return o(x)},children:(p,w)=>{var k=Li(),T=Dt(k);{var C=Y=>{var ie=pc();L(Y,ie)},j=Y=>{var ie=vc(),lt=Q(ie,!0);W(ie),pe(()=>We(lt,o(y).expired)),L(Y,ie)},Ae=Y=>{var ie=gc(),lt=Q(ie,!0);W(ie),pe(()=>{U(ie,"title",o(ge)),We(lt,o(y).error)}),L(Y,ie)};ae(T,Y=>{!o(ge)&&!u?Y(C):!o(ge)&&o(A)===P.EXPIRED?Y(j,1):Y(Ae,-1)})}L(p,k)},$$slots:{default:!0}})},ea=h=>{var p=Li(),w=Dt(p);sl(w,()=>o(oe),k=>{{let T=me(()=>o(m).codeChallengeDisplay!=="standard");kn(k,{get anchor(){return o(R)},get backdrop(){return o(T)},get display(){return o(m).codeChallengeDisplay},onClose:()=>{$e()},get placement(){return o(m).popoverPlacement},role:"dialog",get"aria-label"(){return o(y).verificationRequired},get dir(){return o(Yt)},get updateUISignal(){return o(x)},children:(C,j)=>{var Ae=mc(),Y=Dt(Ae);Qs(Y,{get audioUrl(){return o(te)},get imageUrl(){return o(At)},onCancel:()=>$e(),onReload:()=>It(),onSubmit:ct=>Ei(ct),get codeChallenge(){return o(oe)},get config(){return o(m)},get strings(){return o(y)}});var ie=G(Y,2);{var lt=ct=>{En(ct,{get logo(){return o(Ht)},get strings(){return o(y)}})};ae(ie,ct=>{o(kt)&&o(m).codeChallengeDisplay!=="standard"&&ct(lt)})}L(C,Ae)},$$slots:{default:!0}})}}),L(h,p)};ae(Xo,h=>{o(ge)||o(A)===P.EXPIRED||!u?h(Qo):o(oe)&&o(A)===P.CODE&&h(ea,1)})}W(Ye),gt(Ye,h=>_(R,h),()=>o(R)),pe(h=>{U(Ye,"data-state",o(A)),U(Ye,"data-display",o(m).display||void 0),U(Ye,"data-placement",h),U(Ye,"data-visible",o(ke)||void 0),U(Ye,"dir",o(Yt)),U(tn,"for",o(Et)),Ye.dir=Ye.dir},[()=>_o(o(m).display)]),L(t,ki);var ta=ot(Bo);return s(),ta}typeof window<"u"&&window.customElements&&customElements.define("altcha-widget",_t(yc,{auto:{type:"String"},challenge:{type:"String"},configuration:{type:"String"},display:{type:"String"},language:{type:"String"},name:{type:"String"},theme:{type:"String"},type:{type:"String"},workers:{type:"Number"}},[],["configure","getConfiguration","getState","hide","log","reset","setState","show","updateUI","verify"]));var to=`(function() {
  "use strict";
  function bufferStartsWith(buffer, prefix) {
    if (prefix.length > buffer.length) {
      return false;
    }
    for (let i = 0; i < prefix.length; i++) {
      if (buffer[i] !== prefix[i]) {
        return false;
      }
    }
    return true;
  }
  function bufferToHex(buffer) {
    return Array.from(new Uint8Array(buffer)).map((b) => b.toString(16).padStart(2, "0")).join("");
  }
  function concatBuffers(a, b) {
    const out = new Uint8Array(a.length + b.length);
    out.set(a, 0);
    out.set(b, a.length);
    return out;
  }
  function hexToBuffer(hex) {
    if (hex.length % 2 !== 0) {
      throw new Error(\`Hex string must have an even length. Got: \${hex}\`);
    }
    const buffer = new ArrayBuffer(hex.length / 2);
    const view = new DataView(buffer);
    for (let i = 0; i < hex.length; i += 2) {
      const byteString = hex.substring(i, i + 2);
      const byteValue = parseInt(byteString, 16);
      view.setUint8(i / 2, byteValue);
    }
    return new Uint8Array(buffer);
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     */
    setCounter(n) {
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  async function solveChallenge(options) {
    const {
      challenge,
      controller,
      counterMode = "uint32",
      counterStart = 0,
      counterStep = 1,
      deriveKey: deriveKey2,
      timeout = 9e4
    } = options;
    const { nonce, keyPrefix, salt } = challenge.parameters;
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixBuf = keyPrefix.length % 2 === 0 ? hexToBuffer(keyPrefix) : null;
    const password = new PasswordBuffer(nonceBuf, counterMode);
    const start = performance.now();
    let counter = counterStart;
    let iterations = 0;
    let derivedKeyHex = "";
    let lastYield = start;
    while (true) {
      if (controller?.signal.aborted || timeout && iterations % 10 === 0 && performance.now() - start > timeout) {
        return null;
      }
      const { derivedKey } = await deriveKey2(
        challenge.parameters,
        saltBuf,
        password.setCounter(counter)
      );
      if (iterations % 10 === 0 && performance.now() - lastYield > 200) {
        await delay(0);
        lastYield = performance.now();
      }
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefix)) {
        derivedKeyHex = bufferToHex(derivedKey);
        break;
      }
      counter = counter + counterStep;
      iterations = iterations + 1;
    }
    return {
      counter,
      derivedKey: derivedKeyHex,
      time: timeDuration(start)
    };
  }
  function handler(options) {
    const { deriveKey: deriveKey2 } = options;
    let controller = void 0;
    self.onmessage = async (message) => {
      const { challenge, counterMode, counterStart, counterStep, timeout, type } = message.data;
      if (type === "abort") {
        controller?.abort();
      } else if (type === "work") {
        controller = new AbortController();
        let solution;
        try {
          solution = await solveChallenge({
            challenge,
            controller,
            counterStart,
            counterStep,
            deriveKey: deriveKey2,
            counterMode,
            timeout
          });
        } catch (err) {
          return self.postMessage({ error: err });
        }
        self.postMessage(solution);
      }
    };
  }
  function getDigest(algorithm) {
    switch (algorithm) {
      case "PBKDF2/SHA-512":
        return "SHA-512";
      case "PBKDF2/SHA-384":
        return "SHA-384";
      case "PBKDF2/SHA-256":
      default:
        return "SHA-256";
    }
  }
  async function deriveKey(parameters, salt, password) {
    const { algorithm, cost, keyLength = 32 } = parameters;
    const passwordKey = await crypto.subtle.importKey(
      "raw",
      password,
      { name: "PBKDF2" },
      false,
      ["deriveKey"]
    );
    const derivedKey = await crypto.subtle.deriveKey(
      {
        name: "PBKDF2",
        salt,
        iterations: cost,
        hash: getDigest(algorithm)
      },
      passwordKey,
      { name: "AES-GCM", length: keyLength * 8 },
      true,
      ["encrypt"]
    );
    return {
      derivedKey: new Uint8Array(await crypto.subtle.exportKey("raw", derivedKey))
    };
  }
  handler({
    deriveKey
  });
})();
`,Yi=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",to],{type:"text/javascript;charset=utf-8"});function Kn(t){let e;try{if(e=Yi&&(self.URL||self.webkitURL).createObjectURL(Yi),!e)throw"";let r=new Worker(e,{name:t?.name});return r.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),r}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(to),{name:t?.name})}}var ro=`(function() {
  "use strict";
  function bufferStartsWith(buffer, prefix) {
    if (prefix.length > buffer.length) {
      return false;
    }
    for (let i = 0; i < prefix.length; i++) {
      if (buffer[i] !== prefix[i]) {
        return false;
      }
    }
    return true;
  }
  function bufferToHex(buffer) {
    return Array.from(new Uint8Array(buffer)).map((b) => b.toString(16).padStart(2, "0")).join("");
  }
  function concatBuffers(a, b) {
    const out = new Uint8Array(a.length + b.length);
    out.set(a, 0);
    out.set(b, a.length);
    return out;
  }
  function hexToBuffer(hex) {
    if (hex.length % 2 !== 0) {
      throw new Error(\`Hex string must have an even length. Got: \${hex}\`);
    }
    const buffer = new ArrayBuffer(hex.length / 2);
    const view = new DataView(buffer);
    for (let i = 0; i < hex.length; i += 2) {
      const byteString = hex.substring(i, i + 2);
      const byteValue = parseInt(byteString, 16);
      view.setUint8(i / 2, byteValue);
    }
    return new Uint8Array(buffer);
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     */
    setCounter(n) {
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  async function solveChallenge(options) {
    const {
      challenge,
      controller,
      counterMode = "uint32",
      counterStart = 0,
      counterStep = 1,
      deriveKey: deriveKey2,
      timeout = 9e4
    } = options;
    const { nonce, keyPrefix, salt } = challenge.parameters;
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixBuf = keyPrefix.length % 2 === 0 ? hexToBuffer(keyPrefix) : null;
    const password = new PasswordBuffer(nonceBuf, counterMode);
    const start = performance.now();
    let counter = counterStart;
    let iterations = 0;
    let derivedKeyHex = "";
    let lastYield = start;
    while (true) {
      if (controller?.signal.aborted || timeout && iterations % 10 === 0 && performance.now() - start > timeout) {
        return null;
      }
      const { derivedKey } = await deriveKey2(
        challenge.parameters,
        saltBuf,
        password.setCounter(counter)
      );
      if (iterations % 10 === 0 && performance.now() - lastYield > 200) {
        await delay(0);
        lastYield = performance.now();
      }
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefix)) {
        derivedKeyHex = bufferToHex(derivedKey);
        break;
      }
      counter = counter + counterStep;
      iterations = iterations + 1;
    }
    return {
      counter,
      derivedKey: derivedKeyHex,
      time: timeDuration(start)
    };
  }
  function handler(options) {
    const { deriveKey: deriveKey2 } = options;
    let controller = void 0;
    self.onmessage = async (message) => {
      const { challenge, counterMode, counterStart, counterStep, timeout, type } = message.data;
      if (type === "abort") {
        controller?.abort();
      } else if (type === "work") {
        controller = new AbortController();
        let solution;
        try {
          solution = await solveChallenge({
            challenge,
            controller,
            counterStart,
            counterStep,
            deriveKey: deriveKey2,
            counterMode,
            timeout
          });
        } catch (err) {
          return self.postMessage({ error: err });
        }
        self.postMessage(solution);
      }
    };
  }
  async function deriveKey(parameters, salt, password) {
    const { algorithm, keyLength = 32 } = parameters;
    const iterations = Math.max(1, parameters.cost);
    let data = void 0;
    let derivedKey = void 0;
    for (let i = 0; i < iterations; i++) {
      if (i === 0) {
        data = concatBuffers(salt, password);
      } else {
        data = derivedKey;
      }
      derivedKey = new Uint8Array(
        (await crypto.subtle.digest(algorithm, data)).slice(0, keyLength)
      );
    }
    return {
      parameters: {},
      derivedKey
    };
  }
  handler({
    deriveKey
  });
})();
`,Wi=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",ro],{type:"text/javascript;charset=utf-8"});function zn(t){let e;try{if(e=Wi&&(self.URL||self.webkitURL).createObjectURL(Wi),!e)throw"";let r=new Worker(e,{name:t?.name});return r.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),r}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(ro),{name:t?.name})}}var wc=`:root {
  --altcha-border-color: var(--altcha-color-neutral);
  --altcha-border-width: 1px;
  --altcha-border-radius: 6px;
  --altcha-color-base: light-dark(oklch(100% 0.00011 271.152), oklch(20.904% 0.00002 271.152));
  --altcha-color-base-content: light-dark(
  	oklch(20.904% 0.00002 271.152),
  	oklch(100% 0.00011 271.152)
  );
  --altcha-color-error: oklch(51.284% 0.20527 28.678);
  --altcha-color-error-content: oklch(100% 0.00011 271.152);
  --altcha-color-neutral: light-dark(oklch(83.591% 0.0001 271.152), oklch(46.04% 0.00005 271.152));
  --altcha-color-neutral-content: light-dark(
  	oklch(46.76% 0.00005 271.152),
  	oklch(100% 0.00011 271.152)
  );
  --altcha-color-primary: oklch(40.279% 0.2449 268.131);
  --altcha-color-primary-content: oklch(100% 0.00011 271.152);
  --altcha-color-success: oklch(55.748% 0.18968 142.511);
  --altcha-color-success-content: oklch(100% 0.00011 271.152);
  --altcha-checkbox-border-color: light-dark(
  	oklch(66.494% 0.00233 15.434),
  	oklch(51.028% 0.00006 271.152)
  );
  --altcha-checkbox-border-radius: 5px;
  --altcha-checkbox-border-width: var(--altcha-border-width);
  --altcha-checkbox-outline: 2px solid var(--altcha-checkbox-outline-color);
  --altcha-checkbox-outline-color: -webkit-focus-ring-color;
  --altcha-checkbox-outline-offset: 2px;
  --altcha-checkbox-size: 22px;
  --altcha-checkbox-transition-duration: var(--altcha-transition-duration);
  --altcha-input-background-color: var(--altcha-color-base);
  --altcha-input-border-radius: 3px;
  --altcha-input-border-width: 1px;
  --altcha-input-color: var(--altcha-color-base-content);
  --altcha-max-width: 320px;
  --altcha-padding: 0.75rem;
  --altcha-popover-arrow-size: 6px;
  --altcha-popover-color: var(--altcha-border-color);
  --altcha-shadow: drop-shadow(3px 3px 6px oklch(0% 0 0 / 0.2));
  --altcha-spinner-color: var(--altcha-color-base-content);
  --altcha-switch-background-color: var(--altcha-color-neutral);
  --altcha-switch-border-radius: calc(infinity * 1px);
  --altcha-switch-height: var(--altcha-checkbox-size);
  --altcha-switch-padding: 0.25rem;
  --altcha-switch-width: calc(var(--altcha-checkbox-size) * 1.75);
  --altcha-switch-toggle-border-radius: 100%;
  --altcha-switch-toggle-color: var(--altcha-color-neutral-content);
  --altcha-switch-toggle-size: calc(
  	var(--altcha-switch-height) - calc(var(--altcha-switch-padding) * 2)
  );
  --altcha-transition-duration: 0.6s;
  --altcha-z-index: 99999999;
  --altcha-z-index-popover: 999999999;
}

@supports (-moz-appearance: none) {
  :root {
    --altcha-checkbox-outline-color: var(--altcha-color-primary);
  }
}
.altcha {
  all: revert-layer;
  display: none;
  font-family: inherit;
  font-size: inherit;
  position: relative;
}
.altcha[data-visible] {
  display: block;
}
.altcha-popover, .altcha-popover * {
  all: revert-layer;
  box-sizing: border-box;
  font-family: inherit;
  font-size: inherit;
  line-height: 1.25;
}
.altcha * {
  all: revert-layer;
  box-sizing: border-box;
  font-family: inherit;
  font-size: inherit;
  line-height: 1.25;
}
.altcha a, .altcha-popover a {
  color: currentColor;
  text-decoration: none;
}
.altcha a:hover, .altcha-popover a:hover {
  color: currentColor;
}
.altcha-main {
  align-items: start;
  background-color: var(--altcha-color-base);
  border: var(--altcha-border-width, 1px) solid var(--altcha-border-color);
  border-radius: var(--altcha-border-radius, 0);
  color: var(--altcha-color-base-content);
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  justify-content: space-between;
  padding: var(--altcha-padding);
  max-width: var(--altcha-max-width, 100%);
}
.altcha-main > * {
  display: flex;
  width: 100%;
}
.altcha-main > *:first-child {
  flex-grow: 1;
}
.altcha-checkbox-wrap {
  align-items: center;
  display: flex;
  flex-direction: row;
  flex-grow: 1;
  gap: 0.5rem;
}
.altcha-checkbox-wrap > * {
  display: flex;
}
.altcha-logo {
  opacity: 0.7;
}
.altcha-footer {
  align-items: center;
  display: flex;
  flex-grow: 1;
  gap: 0.5rem;
  justify-content: flex-end;
  font-size: 0.7rem;
  opacity: 0.7;
}
.altcha-footer p {
  margin: 0;
  padding: 0;
}
.altcha-error {
  font-size: 0.85rem;
}
.altcha-button {
  align-items: center;
  background: var(--altcha-color-primary);
  border: var(--altcha-input-border-width) solid var(--altcha-color-primary);
  border-radius: var(--altcha-input-border-radius);
  color: var(--altcha-color-primary-content);
  cursor: pointer;
  display: flex;
  font-size: 0.9rem;
  gap: 0.5rem;
  padding: 0.35rem;
}
.altcha-button:focus {
  border-color: var(--altcha-color-primary);
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-button > .altcha-spinner, .altcha-button > svg {
  height: 20px;
  width: 20px;
}
.altcha-button-secondary {
  background: transparent;
  border-color: var(--altcha-color-neutral);
  color: var(--altcha-color-neutral-content);
}
.altcha-input {
  background: var(--altcha-input-background-color);
  border: var(--altcha-input-border-width) solid var(--altcha-color-neutral);
  border-radius: var(--altcha-input-border-radius);
  color: var(--altcha-input-color);
  flex-grow: 1;
  font-size: 1rem;
  min-width: 0;
  padding: 0.25rem;
  width: auto;
}
.altcha-input:focus {
  border-color: var(--altcha-color-primary);
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-spinner {
  animation: altcha-rotate 0.6s linear infinite;
  border-radius: 100%;
  border: var(--altcha-checkbox-border-width) solid var(--altcha-spinner-color);
  border-bottom-color: transparent;
  border-right-color: transparent;
  opacity: 0.7;
}
.altcha-popover {
  background-color: var(--altcha-color-base);
  border: var(--altcha-border-width) solid var(--altcha-border-color);
  border-radius: var(--altcha-border-radius);
  color: var(--altcha-color-base-content);
  filter: var(--altcha-shadow);
  position: absolute;
  left: calc(var(--altcha-padding) / 2);
  max-width: calc(var(--altcha-max-width) - var(--altcha-padding));
  top: calc(var(--altcha-padding) + var(--altcha-checkbox-size) + var(--altcha-popover-arrow-size));
  z-index: var(--altcha-z-index-popover);
}
.altcha-popover-arrow {
  border: var(--altcha-popover-arrow-size) solid transparent;
  border-bottom-color: var(--altcha-popover-color);
  content: "";
  height: 0;
  left: calc(var(--altcha-checkbox-size) / 2);
  position: absolute;
  top: calc(var(--altcha-popover-arrow-size) * -2);
  width: 0;
}
.altcha-popover-content {
  max-height: 100dvh;
  overflow: auto;
  padding: var(--altcha-padding);
}
.altcha-popover[data-top=true][data-display=standard] {
  bottom: calc(100% - (var(--altcha-padding) - var(--altcha-popover-arrow-size)));
  top: auto;
}
.altcha-popover[data-top=true][data-display=standard] .altcha-popover-arrow {
  border-bottom-color: transparent;
  border-top-color: var(--altcha-popover-color);
  bottom: calc(var(--altcha-popover-arrow-size) * -2);
  top: auto;
}
.altcha-popover[data-variant=error] {
  --altcha-popover-color: var(--altcha-color-error);
  background-color: var(--altcha-color-error);
  border-color: var(--altcha-color-error);
  color: var(--altcha-color-error-content);
}
.altcha-popover[data-variant=error] .altcha-popover-content {
  padding: calc(var(--altcha-padding) / 1.5) var(--altcha-padding);
}
.altcha-popover[data-display=overlay] {
  animation: altcha-overlay-slidein 0.5s forwards;
  left: 50%;
  position: fixed;
  top: 45%;
  transform: translate(-50%, -50%);
  width: var(--altcha-max-width);
  z-index: var(--altcha-z-index);
}
.altcha-popover[data-display=bottomsheet] {
  animation: altcha-bottomsheet-slideup 0.5s forwards;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  border-bottom: 0;
  bottom: -100%;
  left: 50%;
  position: fixed;
  top: auto;
  transform: translate(-50%, 0);
  width: var(--altcha-max-width);
  z-index: var(--altcha-z-index);
}
.altcha-popover[data-display=bottomsheet] .altcha-popover-content {
  padding-bottom: calc(var(--altcha-padding) * 2);
}
.altcha-popover-backdrop {
  background: var(--altcha-color-base-content);
  bottom: 0;
  left: 0;
  opacity: 0.1;
  position: fixed;
  right: 0;
  top: 0;
  transition: opacity 0.5s;
  z-index: var(--altcha-z-index);
}
.altcha-popover-close {
  color: var(--altcha-color-base-content);
  cursor: pointer;
  display: inline-block;
  font-size: 1rem;
  height: 1.25rem;
  line-height: 0.95;
  position: absolute;
  right: 0;
  text-align: center;
  text-shadow: 0 0 1px var(--altcha-color-base);
  top: -1.5rem;
  width: 1.25rem;
  z-index: var(--altcha-z-index);
}
[dir=rtl] .altcha-popover {
  left: auto;
  right: calc(var(--altcha-padding) / 2);
}
[dir=rtl] .altcha-popover-arrow {
  left: auto;
  right: calc(var(--altcha-checkbox-size) / 2);
}
[dir=rtl] .altcha-popover-close {
  left: 0;
  right: auto;
}
.altcha-popover[data-display=bottomsheet] .altcha-footer, .altcha-popover[data-display=overlay] .altcha-footer {
  align-items: center;
  justify-content: center;
  padding-top: 1rem;
  gap: 0.5rem;
}
.altcha-popover[data-display=bottomsheet] .altcha-footer svg, .altcha-popover[data-display=overlay] .altcha-footer svg {
  height: 18px;
  width: 18px;
  vertical-align: middle;
}
.altcha-code-challenge > form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.altcha-code-challenge-title {
  font-weight: 600;
}
.altcha-code-challenge-text {
  font-size: 0.85rem;
}
.altcha-code-challenge-image {
  background: white;
  border: var(--altcha-input-border-width) solid var(--altcha-color-neutral);
  border-radius: var(--altcha-input-border-radius);
  object-fit: contain;
  height: 50px;
}
.altcha-code-challenge-row {
  display: flex;
  gap: 0.5rem;
}
.altcha-code-challenge-buttons {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: var(--altcha-padding);
  justify-content: space-between;
}
.altcha-code-challenge-buttons button {
  justify-content: center;
  width: 100%;
}
.altcha-checkbox {
  cursor: pointer;
  height: var(--altcha-checkbox-size);
  position: relative;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox input {
  appearance: none;
  background: var(--altcha-input-background-color);
  border: var(--altcha-checkbox-border-width, 2px) solid var(--altcha-checkbox-border-color);
  border-radius: var(--altcha-checkbox-border-radius);
  cursor: pointer;
  height: var(--altcha-checkbox-size);
  left: 0;
  margin: 0;
  padding: 0;
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox input:before {
  border-radius: var(--altcha-checkbox-border-radius);
  content: "";
  width: 100%;
  height: 100%;
  background: var(--altcha-color-neutral);
  display: block;
  transform: scale(0);
}
.altcha-checkbox input:checked {
  background-color: var(--altcha-color-success);
  border-color: var(--altcha-color-success);
}
.altcha-checkbox input:checked::before {
  background-color: var(--altcha-color-success);
  opacity: 0;
  transform: scale(2.2);
  transition: all var(--altcha-checkbox-transition-duration) ease;
  transition-delay: 0.1s;
}
.altcha-checkbox svg {
  --altcha-radio-svg-size: calc(var(--altcha-checkbox-size) * 0.5);
  --altcha-radio-svg-offset: calc(var(--altcha-checkbox-size) * 0.25);
  fill: none;
  left: var(--altcha-radio-svg-offset);
  height: var(--altcha-radio-svg-size);
  opacity: 0;
  position: absolute;
  stroke: currentColor;
  stroke-width: 2;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 16px;
  stroke-dashoffset: 16px;
  top: var(--altcha-radio-svg-offset);
  transform: translate3d(0, 0, 0);
  width: var(--altcha-radio-svg-size);
}
.altcha-checkbox input:checked + svg {
  color: var(--altcha-color-success-content);
  opacity: 1;
  stroke-dashoffset: 0;
  transition: all var(--altcha-checkbox-transition-duration) ease;
  transition-delay: 0.1s;
}
.altcha-checkbox-spinner {
  display: none;
  left: 0;
  height: var(--altcha-checkbox-size);
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox[data-loading=true] input {
  appearance: none;
  opacity: 0;
  pointer-events: none;
}
.altcha-checkbox[data-loading=true] .altcha-checkbox-spinner {
  display: block;
}
.altcha-checkbox-native {
  height: var(--altcha-checkbox-size);
  position: relative;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native input {
  height: var(--altcha-checkbox-size);
  margin: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native-spinner {
  display: none;
  left: 0;
  height: var(--altcha-checkbox-size);
  position: absolute;
  top: 0;
  width: var(--altcha-checkbox-size);
}
.altcha-checkbox-native[data-loading=true] input {
  appearance: none;
  opacity: 0;
  pointer-events: none;
}
.altcha-checkbox-native[data-loading=true] .altcha-checkbox-native-spinner {
  display: block;
}
.altcha-switch {
  align-items: center;
  border-radius: var(--altcha-switch-border-radius);
  background-color: var(--altcha-switch-background-color);
  display: flex;
  height: var(--altcha-switch-height);
  padding: var(--altcha-switch-padding);
  position: relative;
  width: var(--altcha-switch-width);
}
.altcha-switch:focus-within {
  outline: var(--altcha-checkbox-outline);
  outline-offset: var(--altcha-checkbox-outline-offset);
}
.altcha-switch input {
  appearance: none;
  cursor: pointer;
  height: 100%;
  left: 0;
  opacity: 0;
  position: absolute;
  top: 0;
  width: 100%;
}
.altcha-switch-toggle {
  align-items: center;
  background-color: var(--altcha-switch-toggle-color);
  border-radius: var(--altcha-switch-toggle-border-radius);
  cursor: pointer;
  display: flex;
  height: var(--altcha-switch-toggle-size);
  justify-content: center;
  left: var(--altcha-switch-padding);
  position: absolute;
  transition: width 150ms ease-out, left 150ms ease-out;
  width: var(--altcha-switch-toggle-size);
}
.altcha-switch-spinner {
  display: none;
  height: var(--altcha-switch-toggle-size);
  width: var(--altcha-switch-toggle-size);
}
.altcha-switch[data-loading=true] {
  pointer-events: none;
}
.altcha-switch[data-loading=true] .altcha-switch-spinner {
  display: block;
}
.altcha-switch[data-loading=true] .altcha-switch-toggle {
  background-color: transparent;
  left: calc(50% - var(--altcha-switch-toggle-size) / 2);
}
[data-state=verified] .altcha-switch {
  --altcha-switch-background-color: var(--altcha-color-success);
}
[data-state=verified] .altcha-switch-toggle {
  background-color: var(--altcha-color-success-content);
  left: calc(100% - var(--altcha-switch-height) + var(--altcha-switch-padding));
}
[dir=rtl] .altcha-switch-toggle {
  left: calc(100% - var(--altcha-switch-height) + var(--altcha-switch-padding));
}
[dir=rtl][data-state=verified] .altcha-switch-toggle {
  left: var(--altcha-switch-padding);
}
.altcha-floating-arrow {
  border: 6px solid transparent;
  border-bottom-color: var(--altcha-border-color);
  content: "";
  height: 0;
  left: 12px;
  position: absolute;
  top: -12px;
  width: 0;
}
.altcha-overlay-backdrop {
  bottom: 0;
  left: 0;
  position: fixed;
  right: 0;
  top: 0;
  transition: opacity var(--altcha-transition-duration);
  z-index: var(--altcha-z-index);
}
.altcha-overlay-close {
  display: inline-block;
  color: currentColor;
  cursor: pointer;
  font-size: 1rem;
  height: 1rem;
  line-height: 0.85;
  position: absolute;
  right: 0;
  text-align: center;
  text-shadow: 0 0 1px var(--altcha-color-base);
  top: -1.5rem;
  width: 1rem;
  z-index: var(--altcha-z-index);
}
.altcha[data-display=overlay] {
  animation: altcha-overlay-slidein var(--altcha-transition-duration) forwards;
  filter: var(--altcha-shadow);
  left: 50%;
  opacity: 0;
  position: fixed;
  top: 45%;
  transform: translate(-50%, -50%);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=overlay] .altcha-main {
  width: var(--altcha-max-width);
}
.altcha[data-display=floating] {
  display: none;
  filter: var(--altcha-shadow);
  left: var(--altcha-floating-left, -100%);
  position: fixed;
  top: var(--altcha-floating-top, -100%);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=floating] .altcha-main {
  width: var(--altcha-max-width);
}
.altcha[data-display=floating][data-floating-position=top] .altcha-floating-arrow {
  border-bottom-color: transparent;
  border-top-color: var(--altcha-border-color);
  bottom: -12px;
  top: auto;
}
.altcha[data-display=floating][data-visible] {
  display: flex;
}
.altcha[data-display=bar] {
  bottom: -100%;
  filter: var(--altcha-shadow);
  left: 0;
  position: fixed;
  right: 0;
  transition: bottom var(--altcha-transition-duration), top var(--altcha-transition-duration);
  z-index: var(--altcha-z-index);
}
.altcha[data-display=bar] .altcha-main {
  align-items: center;
  border-radius: 0;
  border-width: var(--altcha-border-width) 0 0 0;
  flex-direction: row;
  max-width: 100% !important;
}
.altcha[data-display=bar] .altcha-main > * {
  width: auto;
}
.altcha[data-display=bar][data-placement=top] {
  bottom: auto;
  top: -100%;
}
.altcha[data-display=bar][data-placement=top] .altcha-main {
  border-width: 0 0 var(--altcha-border-width) 0;
}
.altcha[data-display=bar][data-placement=bottom]:not([data-state=unverified]) {
  bottom: 0;
}
.altcha[data-display=bar][data-placement=top]:not([data-state=unverified]) {
  top: 0;
}
.altcha[data-display=invisible] {
  display: none;
}

@keyframes altcha-rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
@keyframes altcha-bottomsheet-slideup {
  100% {
    bottom: 0;
  }
}
@keyframes altcha-overlay-slidein {
  100% {
    opacity: 1;
    top: 50%;
  }
}`;lc(wc);$altcha.algorithms.set("SHA-256",()=>new zn);$altcha.algorithms.set("SHA-384",()=>new zn);$altcha.algorithms.set("SHA-512",()=>new zn);$altcha.algorithms.set("PBKDF2/SHA-256",()=>new Kn);$altcha.algorithms.set("PBKDF2/SHA-384",()=>new Kn);$altcha.algorithms.set("PBKDF2/SHA-512",()=>new Kn);var _c={ariaLinkLabel:"Altcha (site officiel)",enterCode:"Entrez le code",enterCodeAria:"Entrez le code que vous entendez. Appuyez sur Espace pour \xE9couter l'audio.",error:"\xC9chec de la v\xE9rification. Essayez \xE0 nouveau plus tard.",expired:"La v\xE9rification a expir\xE9. Essayez \xE0 nouveau.",footer:'Prot\xE9g\xE9 par <a href="https://altcha.org/" tabindex="-1" target="_blank" aria-label="Altcha (site officiel)">ALTCHA</a>',getAudioChallenge:"Obtenir un d\xE9fi audio",label:"Je ne suis pas un robot",loading:"Chargement...",reload:"Recharger",verify:"V\xE9rifier",verificationRequired:"V\xE9rification requise !",verified:"V\xE9rifi\xE9",verifying:"V\xE9rification en cours...",waitAlert:"V\xE9rification en cours... veuillez patienter.",cancel:"Annuler",enterCodeFromImage:"Pour continuer, veuillez entrer le code de l'image ci-dessous."};"$altcha"in globalThis&&globalThis.$altcha.i18n.set("fr-fr",_c);var Hn=class{constructor(e,r,n){this.eventTarget=e,this.eventName=r,this.eventOptions=n,this.unorderedBindings=new Set}connect(){this.eventTarget.addEventListener(this.eventName,this,this.eventOptions)}disconnect(){this.eventTarget.removeEventListener(this.eventName,this,this.eventOptions)}bindingConnected(e){this.unorderedBindings.add(e)}bindingDisconnected(e){this.unorderedBindings.delete(e)}handleEvent(e){let r=Ec(e);for(let n of this.bindings){if(r.immediatePropagationStopped)break;n.handleEvent(r)}}hasBindings(){return this.unorderedBindings.size>0}get bindings(){return Array.from(this.unorderedBindings).sort((e,r)=>{let n=e.index,i=r.index;return n<i?-1:n>i?1:0})}};function Ec(t){if("immediatePropagationStopped"in t)return t;{let{stopImmediatePropagation:e}=t;return Object.assign(t,{immediatePropagationStopped:!1,stopImmediatePropagation(){this.immediatePropagationStopped=!0,e.call(this)}})}}var qn=class{constructor(e){this.application=e,this.eventListenerMaps=new Map,this.started=!1}start(){this.started||(this.started=!0,this.eventListeners.forEach(e=>e.connect()))}stop(){this.started&&(this.started=!1,this.eventListeners.forEach(e=>e.disconnect()))}get eventListeners(){return Array.from(this.eventListenerMaps.values()).reduce((e,r)=>e.concat(Array.from(r.values())),[])}bindingConnected(e){this.fetchEventListenerForBinding(e).bindingConnected(e)}bindingDisconnected(e,r=!1){this.fetchEventListenerForBinding(e).bindingDisconnected(e),r&&this.clearEventListenersForBinding(e)}handleError(e,r,n={}){this.application.handleError(e,`Error ${r}`,n)}clearEventListenersForBinding(e){let r=this.fetchEventListenerForBinding(e);r.hasBindings()||(r.disconnect(),this.removeMappedEventListenerFor(e))}removeMappedEventListenerFor(e){let{eventTarget:r,eventName:n,eventOptions:i}=e,s=this.fetchEventListenerMapForEventTarget(r),a=this.cacheKey(n,i);s.delete(a),s.size==0&&this.eventListenerMaps.delete(r)}fetchEventListenerForBinding(e){let{eventTarget:r,eventName:n,eventOptions:i}=e;return this.fetchEventListener(r,n,i)}fetchEventListener(e,r,n){let i=this.fetchEventListenerMapForEventTarget(e),s=this.cacheKey(r,n),a=i.get(s);return a||(a=this.createEventListener(e,r,n),i.set(s,a)),a}createEventListener(e,r,n){let i=new Hn(e,r,n);return this.started&&i.connect(),i}fetchEventListenerMapForEventTarget(e){let r=this.eventListenerMaps.get(e);return r||(r=new Map,this.eventListenerMaps.set(e,r)),r}cacheKey(e,r){let n=[e];return Object.keys(r).sort().forEach(i=>{n.push(`${r[i]?"":"!"}${i}`)}),n.join(":")}},kc={stop({event:t,value:e}){return e&&t.stopPropagation(),!0},prevent({event:t,value:e}){return e&&t.preventDefault(),!0},self({event:t,value:e,element:r}){return e?r===t.target:!0}},Ac=/^(?:(?:([^.]+?)\+)?(.+?)(?:\.(.+?))?(?:@(window|document))?->)?(.+?)(?:#([^:]+?))(?::(.+))?$/;function xc(t){let r=t.trim().match(Ac)||[],n=r[2],i=r[3];return i&&!["keydown","keyup","keypress"].includes(n)&&(n+=`.${i}`,i=""),{eventTarget:Cc(r[4]),eventName:n,eventOptions:r[7]?Oc(r[7]):{},identifier:r[5],methodName:r[6],keyFilter:r[1]||i}}function Cc(t){if(t=="window")return window;if(t=="document")return document}function Oc(t){return t.split(":").reduce((e,r)=>Object.assign(e,{[r.replace(/^!/,"")]:!/^!/.test(r)}),{})}function Tc(t){if(t==window)return"window";if(t==document)return"document"}function pi(t){return t.replace(/(?:[_-])([a-z0-9])/g,(e,r)=>r.toUpperCase())}function Yn(t){return pi(t.replace(/--/g,"-").replace(/__/g,"_"))}function vr(t){return t.charAt(0).toUpperCase()+t.slice(1)}function fo(t){return t.replace(/([A-Z])/g,(e,r)=>`-${r.toLowerCase()}`)}function Sc(t){return t.match(/[^\s]+/g)||[]}function no(t){return t!=null}function Wn(t,e){return Object.prototype.hasOwnProperty.call(t,e)}var io=["meta","ctrl","alt","shift"],Gn=class{constructor(e,r,n,i){this.element=e,this.index=r,this.eventTarget=n.eventTarget||e,this.eventName=n.eventName||$c(e)||Br("missing event name"),this.eventOptions=n.eventOptions||{},this.identifier=n.identifier||Br("missing identifier"),this.methodName=n.methodName||Br("missing method name"),this.keyFilter=n.keyFilter||"",this.schema=i}static forToken(e,r){return new this(e.element,e.index,xc(e.content),r)}toString(){let e=this.keyFilter?`.${this.keyFilter}`:"",r=this.eventTargetName?`@${this.eventTargetName}`:"";return`${this.eventName}${e}${r}->${this.identifier}#${this.methodName}`}shouldIgnoreKeyboardEvent(e){if(!this.keyFilter)return!1;let r=this.keyFilter.split("+");if(this.keyFilterDissatisfied(e,r))return!0;let n=r.filter(i=>!io.includes(i))[0];return n?(Wn(this.keyMappings,n)||Br(`contains unknown key filter: ${this.keyFilter}`),this.keyMappings[n].toLowerCase()!==e.key.toLowerCase()):!1}shouldIgnoreMouseEvent(e){if(!this.keyFilter)return!1;let r=[this.keyFilter];return!!this.keyFilterDissatisfied(e,r)}get params(){let e={},r=new RegExp(`^data-${this.identifier}-(.+)-param$`,"i");for(let{name:n,value:i}of Array.from(this.element.attributes)){let s=n.match(r),a=s&&s[1];a&&(e[pi(a)]=Mc(i))}return e}get eventTargetName(){return Tc(this.eventTarget)}get keyMappings(){return this.schema.keyMappings}keyFilterDissatisfied(e,r){let[n,i,s,a]=io.map(l=>r.includes(l));return e.metaKey!==n||e.ctrlKey!==i||e.altKey!==s||e.shiftKey!==a}},so={a:()=>"click",button:()=>"click",form:()=>"submit",details:()=>"toggle",input:t=>t.getAttribute("type")=="submit"?"click":"input",select:()=>"change",textarea:()=>"input"};function $c(t){let e=t.tagName.toLowerCase();if(e in so)return so[e](t)}function Br(t){throw new Error(t)}function Mc(t){try{return JSON.parse(t)}catch{return t}}var Jn=class{constructor(e,r){this.context=e,this.action=r}get index(){return this.action.index}get eventTarget(){return this.action.eventTarget}get eventOptions(){return this.action.eventOptions}get identifier(){return this.context.identifier}handleEvent(e){let r=this.prepareActionEvent(e);this.willBeInvokedByEvent(e)&&this.applyEventModifiers(r)&&this.invokeWithEvent(r)}get eventName(){return this.action.eventName}get method(){let e=this.controller[this.methodName];if(typeof e=="function")return e;throw new Error(`Action "${this.action}" references undefined method "${this.methodName}"`)}applyEventModifiers(e){let{element:r}=this.action,{actionDescriptorFilters:n}=this.context.application,{controller:i}=this.context,s=!0;for(let[a,l]of Object.entries(this.eventOptions))if(a in n){let c=n[a];s=s&&c({name:a,value:l,event:e,element:r,controller:i})}else continue;return s}prepareActionEvent(e){return Object.assign(e,{params:this.action.params})}invokeWithEvent(e){let{target:r,currentTarget:n}=e;try{this.method.call(this.controller,e),this.context.logDebugActivity(this.methodName,{event:e,target:r,currentTarget:n,action:this.methodName})}catch(i){let{identifier:s,controller:a,element:l,index:c}=this,u={identifier:s,controller:a,element:l,index:c,event:e};this.context.handleError(i,`invoking action "${this.action}"`,u)}}willBeInvokedByEvent(e){let r=e.target;return e instanceof KeyboardEvent&&this.action.shouldIgnoreKeyboardEvent(e)||e instanceof MouseEvent&&this.action.shouldIgnoreMouseEvent(e)?!1:this.element===r?!0:r instanceof Element&&this.element.contains(r)?this.scope.containsElement(r):this.scope.containsElement(this.action.element)}get controller(){return this.context.controller}get methodName(){return this.action.methodName}get element(){return this.scope.element}get scope(){return this.context.scope}},Pr=class{constructor(e,r){this.mutationObserverInit={attributes:!0,childList:!0,subtree:!0},this.element=e,this.started=!1,this.delegate=r,this.elements=new Set,this.mutationObserver=new MutationObserver(n=>this.processMutations(n))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,this.mutationObserverInit),this.refresh())}pause(e){this.started&&(this.mutationObserver.disconnect(),this.started=!1),e(),this.started||(this.mutationObserver.observe(this.element,this.mutationObserverInit),this.started=!0)}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started){let e=new Set(this.matchElementsInTree());for(let r of Array.from(this.elements))e.has(r)||this.removeElement(r);for(let r of Array.from(e))this.addElement(r)}}processMutations(e){if(this.started)for(let r of e)this.processMutation(r)}processMutation(e){e.type=="attributes"?this.processAttributeChange(e.target,e.attributeName):e.type=="childList"&&(this.processRemovedNodes(e.removedNodes),this.processAddedNodes(e.addedNodes))}processAttributeChange(e,r){this.elements.has(e)?this.delegate.elementAttributeChanged&&this.matchElement(e)?this.delegate.elementAttributeChanged(e,r):this.removeElement(e):this.matchElement(e)&&this.addElement(e)}processRemovedNodes(e){for(let r of Array.from(e)){let n=this.elementFromNode(r);n&&this.processTree(n,this.removeElement)}}processAddedNodes(e){for(let r of Array.from(e)){let n=this.elementFromNode(r);n&&this.elementIsActive(n)&&this.processTree(n,this.addElement)}}matchElement(e){return this.delegate.matchElement(e)}matchElementsInTree(e=this.element){return this.delegate.matchElementsInTree(e)}processTree(e,r){for(let n of this.matchElementsInTree(e))r.call(this,n)}elementFromNode(e){if(e.nodeType==Node.ELEMENT_NODE)return e}elementIsActive(e){return e.isConnected!=this.element.isConnected?!1:this.element.contains(e)}addElement(e){this.elements.has(e)||this.elementIsActive(e)&&(this.elements.add(e),this.delegate.elementMatched&&this.delegate.elementMatched(e))}removeElement(e){this.elements.has(e)&&(this.elements.delete(e),this.delegate.elementUnmatched&&this.delegate.elementUnmatched(e))}},Vr=class{constructor(e,r,n){this.attributeName=r,this.delegate=n,this.elementObserver=new Pr(e,this)}get element(){return this.elementObserver.element}get selector(){return`[${this.attributeName}]`}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get started(){return this.elementObserver.started}matchElement(e){return e.hasAttribute(this.attributeName)}matchElementsInTree(e){let r=this.matchElement(e)?[e]:[],n=Array.from(e.querySelectorAll(this.selector));return r.concat(n)}elementMatched(e){this.delegate.elementMatchedAttribute&&this.delegate.elementMatchedAttribute(e,this.attributeName)}elementUnmatched(e){this.delegate.elementUnmatchedAttribute&&this.delegate.elementUnmatchedAttribute(e,this.attributeName)}elementAttributeChanged(e,r){this.delegate.elementAttributeValueChanged&&this.attributeName==r&&this.delegate.elementAttributeValueChanged(e,r)}};function Fc(t,e,r){po(t,e).add(r)}function Nc(t,e,r){po(t,e).delete(r),Ic(t,e)}function po(t,e){let r=t.get(e);return r||(r=new Set,t.set(e,r)),r}function Ic(t,e){let r=t.get(e);r!=null&&r.size==0&&t.delete(e)}var at=class{constructor(){this.valuesByKey=new Map}get keys(){return Array.from(this.valuesByKey.keys())}get values(){return Array.from(this.valuesByKey.values()).reduce((r,n)=>r.concat(Array.from(n)),[])}get size(){return Array.from(this.valuesByKey.values()).reduce((r,n)=>r+n.size,0)}add(e,r){Fc(this.valuesByKey,e,r)}delete(e,r){Nc(this.valuesByKey,e,r)}has(e,r){let n=this.valuesByKey.get(e);return n!=null&&n.has(r)}hasKey(e){return this.valuesByKey.has(e)}hasValue(e){return Array.from(this.valuesByKey.values()).some(n=>n.has(e))}getValuesForKey(e){let r=this.valuesByKey.get(e);return r?Array.from(r):[]}getKeysForValue(e){return Array.from(this.valuesByKey).filter(([r,n])=>n.has(e)).map(([r,n])=>r)}};var Zn=class{constructor(e,r,n,i){this._selector=r,this.details=i,this.elementObserver=new Pr(e,this),this.delegate=n,this.matchesByElement=new at}get started(){return this.elementObserver.started}get selector(){return this._selector}set selector(e){this._selector=e,this.refresh()}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get element(){return this.elementObserver.element}matchElement(e){let{selector:r}=this;if(r){let n=e.matches(r);return this.delegate.selectorMatchElement?n&&this.delegate.selectorMatchElement(e,this.details):n}else return!1}matchElementsInTree(e){let{selector:r}=this;if(r){let n=this.matchElement(e)?[e]:[],i=Array.from(e.querySelectorAll(r)).filter(s=>this.matchElement(s));return n.concat(i)}else return[]}elementMatched(e){let{selector:r}=this;r&&this.selectorMatched(e,r)}elementUnmatched(e){let r=this.matchesByElement.getKeysForValue(e);for(let n of r)this.selectorUnmatched(e,n)}elementAttributeChanged(e,r){let{selector:n}=this;if(n){let i=this.matchElement(e),s=this.matchesByElement.has(n,e);i&&!s?this.selectorMatched(e,n):!i&&s&&this.selectorUnmatched(e,n)}}selectorMatched(e,r){this.delegate.selectorMatched(e,r,this.details),this.matchesByElement.add(r,e)}selectorUnmatched(e,r){this.delegate.selectorUnmatched(e,r,this.details),this.matchesByElement.delete(r,e)}},Xn=class{constructor(e,r){this.element=e,this.delegate=r,this.started=!1,this.stringMap=new Map,this.mutationObserver=new MutationObserver(n=>this.processMutations(n))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,{attributes:!0,attributeOldValue:!0}),this.refresh())}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started)for(let e of this.knownAttributeNames)this.refreshAttribute(e,null)}processMutations(e){if(this.started)for(let r of e)this.processMutation(r)}processMutation(e){let r=e.attributeName;r&&this.refreshAttribute(r,e.oldValue)}refreshAttribute(e,r){let n=this.delegate.getStringMapKeyForAttribute(e);if(n!=null){this.stringMap.has(e)||this.stringMapKeyAdded(n,e);let i=this.element.getAttribute(e);if(this.stringMap.get(e)!=i&&this.stringMapValueChanged(i,n,r),i==null){let s=this.stringMap.get(e);this.stringMap.delete(e),s&&this.stringMapKeyRemoved(n,e,s)}else this.stringMap.set(e,i)}}stringMapKeyAdded(e,r){this.delegate.stringMapKeyAdded&&this.delegate.stringMapKeyAdded(e,r)}stringMapValueChanged(e,r,n){this.delegate.stringMapValueChanged&&this.delegate.stringMapValueChanged(e,r,n)}stringMapKeyRemoved(e,r,n){this.delegate.stringMapKeyRemoved&&this.delegate.stringMapKeyRemoved(e,r,n)}get knownAttributeNames(){return Array.from(new Set(this.currentAttributeNames.concat(this.recordedAttributeNames)))}get currentAttributeNames(){return Array.from(this.element.attributes).map(e=>e.name)}get recordedAttributeNames(){return Array.from(this.stringMap.keys())}},jr=class{constructor(e,r,n){this.attributeObserver=new Vr(e,r,this),this.delegate=n,this.tokensByElement=new at}get started(){return this.attributeObserver.started}start(){this.attributeObserver.start()}pause(e){this.attributeObserver.pause(e)}stop(){this.attributeObserver.stop()}refresh(){this.attributeObserver.refresh()}get element(){return this.attributeObserver.element}get attributeName(){return this.attributeObserver.attributeName}elementMatchedAttribute(e){this.tokensMatched(this.readTokensForElement(e))}elementAttributeValueChanged(e){let[r,n]=this.refreshTokensForElement(e);this.tokensUnmatched(r),this.tokensMatched(n)}elementUnmatchedAttribute(e){this.tokensUnmatched(this.tokensByElement.getValuesForKey(e))}tokensMatched(e){e.forEach(r=>this.tokenMatched(r))}tokensUnmatched(e){e.forEach(r=>this.tokenUnmatched(r))}tokenMatched(e){this.delegate.tokenMatched(e),this.tokensByElement.add(e.element,e)}tokenUnmatched(e){this.delegate.tokenUnmatched(e),this.tokensByElement.delete(e.element,e)}refreshTokensForElement(e){let r=this.tokensByElement.getValuesForKey(e),n=this.readTokensForElement(e),i=Dc(r,n).findIndex(([s,a])=>!Rc(s,a));return i==-1?[[],[]]:[r.slice(i),n.slice(i)]}readTokensForElement(e){let r=this.attributeName,n=e.getAttribute(r)||"";return Lc(n,e,r)}};function Lc(t,e,r){return t.trim().split(/\s+/).filter(n=>n.length).map((n,i)=>({element:e,attributeName:r,content:n,index:i}))}function Dc(t,e){let r=Math.max(t.length,e.length);return Array.from({length:r},(n,i)=>[t[i],e[i]])}function Rc(t,e){return t&&e&&t.index==e.index&&t.content==e.content}var Ur=class{constructor(e,r,n){this.tokenListObserver=new jr(e,r,this),this.delegate=n,this.parseResultsByToken=new WeakMap,this.valuesByTokenByElement=new WeakMap}get started(){return this.tokenListObserver.started}start(){this.tokenListObserver.start()}stop(){this.tokenListObserver.stop()}refresh(){this.tokenListObserver.refresh()}get element(){return this.tokenListObserver.element}get attributeName(){return this.tokenListObserver.attributeName}tokenMatched(e){let{element:r}=e,{value:n}=this.fetchParseResultForToken(e);n&&(this.fetchValuesByTokenForElement(r).set(e,n),this.delegate.elementMatchedValue(r,n))}tokenUnmatched(e){let{element:r}=e,{value:n}=this.fetchParseResultForToken(e);n&&(this.fetchValuesByTokenForElement(r).delete(e),this.delegate.elementUnmatchedValue(r,n))}fetchParseResultForToken(e){let r=this.parseResultsByToken.get(e);return r||(r=this.parseToken(e),this.parseResultsByToken.set(e,r)),r}fetchValuesByTokenForElement(e){let r=this.valuesByTokenByElement.get(e);return r||(r=new Map,this.valuesByTokenByElement.set(e,r)),r}parseToken(e){try{return{value:this.delegate.parseValueForToken(e)}}catch(r){return{error:r}}}},Qn=class{constructor(e,r){this.context=e,this.delegate=r,this.bindingsByAction=new Map}start(){this.valueListObserver||(this.valueListObserver=new Ur(this.element,this.actionAttribute,this),this.valueListObserver.start())}stop(){this.valueListObserver&&(this.valueListObserver.stop(),delete this.valueListObserver,this.disconnectAllActions())}get element(){return this.context.element}get identifier(){return this.context.identifier}get actionAttribute(){return this.schema.actionAttribute}get schema(){return this.context.schema}get bindings(){return Array.from(this.bindingsByAction.values())}connectAction(e){let r=new Jn(this.context,e);this.bindingsByAction.set(e,r),this.delegate.bindingConnected(r)}disconnectAction(e){let r=this.bindingsByAction.get(e);r&&(this.bindingsByAction.delete(e),this.delegate.bindingDisconnected(r))}disconnectAllActions(){this.bindings.forEach(e=>this.delegate.bindingDisconnected(e,!0)),this.bindingsByAction.clear()}parseValueForToken(e){let r=Gn.forToken(e,this.schema);if(r.identifier==this.identifier)return r}elementMatchedValue(e,r){this.connectAction(r)}elementUnmatchedValue(e,r){this.disconnectAction(r)}},ei=class{constructor(e,r){this.context=e,this.receiver=r,this.stringMapObserver=new Xn(this.element,this),this.valueDescriptorMap=this.controller.valueDescriptorMap}start(){this.stringMapObserver.start(),this.invokeChangedCallbacksForDefaultValues()}stop(){this.stringMapObserver.stop()}get element(){return this.context.element}get controller(){return this.context.controller}getStringMapKeyForAttribute(e){if(e in this.valueDescriptorMap)return this.valueDescriptorMap[e].name}stringMapKeyAdded(e,r){let n=this.valueDescriptorMap[r];this.hasValue(e)||this.invokeChangedCallback(e,n.writer(this.receiver[e]),n.writer(n.defaultValue))}stringMapValueChanged(e,r,n){let i=this.valueDescriptorNameMap[r];e!==null&&(n===null&&(n=i.writer(i.defaultValue)),this.invokeChangedCallback(r,e,n))}stringMapKeyRemoved(e,r,n){let i=this.valueDescriptorNameMap[e];this.hasValue(e)?this.invokeChangedCallback(e,i.writer(this.receiver[e]),n):this.invokeChangedCallback(e,i.writer(i.defaultValue),n)}invokeChangedCallbacksForDefaultValues(){for(let{key:e,name:r,defaultValue:n,writer:i}of this.valueDescriptors)n!=null&&!this.controller.data.has(e)&&this.invokeChangedCallback(r,i(n),void 0)}invokeChangedCallback(e,r,n){let i=`${e}Changed`,s=this.receiver[i];if(typeof s=="function"){let a=this.valueDescriptorNameMap[e];try{let l=a.reader(r),c=n;n&&(c=a.reader(n)),s.call(this.receiver,l,c)}catch(l){throw l instanceof TypeError&&(l.message=`Stimulus Value "${this.context.identifier}.${a.name}" - ${l.message}`),l}}}get valueDescriptors(){let{valueDescriptorMap:e}=this;return Object.keys(e).map(r=>e[r])}get valueDescriptorNameMap(){let e={};return Object.keys(this.valueDescriptorMap).forEach(r=>{let n=this.valueDescriptorMap[r];e[n.name]=n}),e}hasValue(e){let r=this.valueDescriptorNameMap[e],n=`has${vr(r.name)}`;return this.receiver[n]}},ti=class{constructor(e,r){this.context=e,this.delegate=r,this.targetsByName=new at}start(){this.tokenListObserver||(this.tokenListObserver=new jr(this.element,this.attributeName,this),this.tokenListObserver.start())}stop(){this.tokenListObserver&&(this.disconnectAllTargets(),this.tokenListObserver.stop(),delete this.tokenListObserver)}tokenMatched({element:e,content:r}){this.scope.containsElement(e)&&this.connectTarget(e,r)}tokenUnmatched({element:e,content:r}){this.disconnectTarget(e,r)}connectTarget(e,r){var n;this.targetsByName.has(r,e)||(this.targetsByName.add(r,e),(n=this.tokenListObserver)===null||n===void 0||n.pause(()=>this.delegate.targetConnected(e,r)))}disconnectTarget(e,r){var n;this.targetsByName.has(r,e)&&(this.targetsByName.delete(r,e),(n=this.tokenListObserver)===null||n===void 0||n.pause(()=>this.delegate.targetDisconnected(e,r)))}disconnectAllTargets(){for(let e of this.targetsByName.keys)for(let r of this.targetsByName.getValuesForKey(e))this.disconnectTarget(r,e)}get attributeName(){return`data-${this.context.identifier}-target`}get element(){return this.context.element}get scope(){return this.context.scope}};function gr(t,e){let r=vo(t);return Array.from(r.reduce((n,i)=>(Pc(i,e).forEach(s=>n.add(s)),n),new Set))}function Bc(t,e){return vo(t).reduce((n,i)=>(n.push(...Vc(i,e)),n),[])}function vo(t){let e=[];for(;t;)e.push(t),t=Object.getPrototypeOf(t);return e.reverse()}function Pc(t,e){let r=t[e];return Array.isArray(r)?r:[]}function Vc(t,e){let r=t[e];return r?Object.keys(r).map(n=>[n,r[n]]):[]}var ri=class{constructor(e,r){this.started=!1,this.context=e,this.delegate=r,this.outletsByName=new at,this.outletElementsByName=new at,this.selectorObserverMap=new Map,this.attributeObserverMap=new Map}start(){this.started||(this.outletDefinitions.forEach(e=>{this.setupSelectorObserverForOutlet(e),this.setupAttributeObserverForOutlet(e)}),this.started=!0,this.dependentContexts.forEach(e=>e.refresh()))}refresh(){this.selectorObserverMap.forEach(e=>e.refresh()),this.attributeObserverMap.forEach(e=>e.refresh())}stop(){this.started&&(this.started=!1,this.disconnectAllOutlets(),this.stopSelectorObservers(),this.stopAttributeObservers())}stopSelectorObservers(){this.selectorObserverMap.size>0&&(this.selectorObserverMap.forEach(e=>e.stop()),this.selectorObserverMap.clear())}stopAttributeObservers(){this.attributeObserverMap.size>0&&(this.attributeObserverMap.forEach(e=>e.stop()),this.attributeObserverMap.clear())}selectorMatched(e,r,{outletName:n}){let i=this.getOutlet(e,n);i&&this.connectOutlet(i,e,n)}selectorUnmatched(e,r,{outletName:n}){let i=this.getOutletFromMap(e,n);i&&this.disconnectOutlet(i,e,n)}selectorMatchElement(e,{outletName:r}){let n=this.selector(r),i=this.hasOutlet(e,r),s=e.matches(`[${this.schema.controllerAttribute}~=${r}]`);return n?i&&s&&e.matches(n):!1}elementMatchedAttribute(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}elementAttributeValueChanged(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}elementUnmatchedAttribute(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}connectOutlet(e,r,n){var i;this.outletElementsByName.has(n,r)||(this.outletsByName.add(n,e),this.outletElementsByName.add(n,r),(i=this.selectorObserverMap.get(n))===null||i===void 0||i.pause(()=>this.delegate.outletConnected(e,r,n)))}disconnectOutlet(e,r,n){var i;this.outletElementsByName.has(n,r)&&(this.outletsByName.delete(n,e),this.outletElementsByName.delete(n,r),(i=this.selectorObserverMap.get(n))===null||i===void 0||i.pause(()=>this.delegate.outletDisconnected(e,r,n)))}disconnectAllOutlets(){for(let e of this.outletElementsByName.keys)for(let r of this.outletElementsByName.getValuesForKey(e))for(let n of this.outletsByName.getValuesForKey(e))this.disconnectOutlet(n,r,e)}updateSelectorObserverForOutlet(e){let r=this.selectorObserverMap.get(e);r&&(r.selector=this.selector(e))}setupSelectorObserverForOutlet(e){let r=this.selector(e),n=new Zn(document.body,r,this,{outletName:e});this.selectorObserverMap.set(e,n),n.start()}setupAttributeObserverForOutlet(e){let r=this.attributeNameForOutletName(e),n=new Vr(this.scope.element,r,this);this.attributeObserverMap.set(e,n),n.start()}selector(e){return this.scope.outlets.getSelectorForOutletName(e)}attributeNameForOutletName(e){return this.scope.schema.outletAttributeForScope(this.identifier,e)}getOutletNameFromOutletAttributeName(e){return this.outletDefinitions.find(r=>this.attributeNameForOutletName(r)===e)}get outletDependencies(){let e=new at;return this.router.modules.forEach(r=>{let n=r.definition.controllerConstructor;gr(n,"outlets").forEach(s=>e.add(s,r.identifier))}),e}get outletDefinitions(){return this.outletDependencies.getKeysForValue(this.identifier)}get dependentControllerIdentifiers(){return this.outletDependencies.getValuesForKey(this.identifier)}get dependentContexts(){let e=this.dependentControllerIdentifiers;return this.router.contexts.filter(r=>e.includes(r.identifier))}hasOutlet(e,r){return!!this.getOutlet(e,r)||!!this.getOutletFromMap(e,r)}getOutlet(e,r){return this.application.getControllerForElementAndIdentifier(e,r)}getOutletFromMap(e,r){return this.outletsByName.getValuesForKey(r).find(n=>n.element===e)}get scope(){return this.context.scope}get schema(){return this.context.schema}get identifier(){return this.context.identifier}get application(){return this.context.application}get router(){return this.application.router}},ni=class{constructor(e,r){this.logDebugActivity=(n,i={})=>{let{identifier:s,controller:a,element:l}=this;i=Object.assign({identifier:s,controller:a,element:l},i),this.application.logDebugActivity(this.identifier,n,i)},this.module=e,this.scope=r,this.controller=new e.controllerConstructor(this),this.bindingObserver=new Qn(this,this.dispatcher),this.valueObserver=new ei(this,this.controller),this.targetObserver=new ti(this,this),this.outletObserver=new ri(this,this);try{this.controller.initialize(),this.logDebugActivity("initialize")}catch(n){this.handleError(n,"initializing controller")}}connect(){this.bindingObserver.start(),this.valueObserver.start(),this.targetObserver.start(),this.outletObserver.start();try{this.controller.connect(),this.logDebugActivity("connect")}catch(e){this.handleError(e,"connecting controller")}}refresh(){this.outletObserver.refresh()}disconnect(){try{this.controller.disconnect(),this.logDebugActivity("disconnect")}catch(e){this.handleError(e,"disconnecting controller")}this.outletObserver.stop(),this.targetObserver.stop(),this.valueObserver.stop(),this.bindingObserver.stop()}get application(){return this.module.application}get identifier(){return this.module.identifier}get schema(){return this.application.schema}get dispatcher(){return this.application.dispatcher}get element(){return this.scope.element}get parentElement(){return this.element.parentElement}handleError(e,r,n={}){let{identifier:i,controller:s,element:a}=this;n=Object.assign({identifier:i,controller:s,element:a},n),this.application.handleError(e,`Error ${r}`,n)}targetConnected(e,r){this.invokeControllerMethod(`${r}TargetConnected`,e)}targetDisconnected(e,r){this.invokeControllerMethod(`${r}TargetDisconnected`,e)}outletConnected(e,r,n){this.invokeControllerMethod(`${Yn(n)}OutletConnected`,e,r)}outletDisconnected(e,r,n){this.invokeControllerMethod(`${Yn(n)}OutletDisconnected`,e,r)}invokeControllerMethod(e,...r){let n=this.controller;typeof n[e]=="function"&&n[e](...r)}};function jc(t){return Uc(t,Kc(t))}function Uc(t,e){let r=Yc(t),n=zc(t.prototype,e);return Object.defineProperties(r.prototype,n),r}function Kc(t){return gr(t,"blessings").reduce((r,n)=>{let i=n(t);for(let s in i){let a=r[s]||{};r[s]=Object.assign(a,i[s])}return r},{})}function zc(t,e){return qc(e).reduce((r,n)=>{let i=Hc(t,e,n);return i&&Object.assign(r,{[n]:i}),r},{})}function Hc(t,e,r){let n=Object.getOwnPropertyDescriptor(t,r);if(!(n&&"value"in n)){let s=Object.getOwnPropertyDescriptor(e,r).value;return n&&(s.get=n.get||s.get,s.set=n.set||s.set),s}}var qc=typeof Object.getOwnPropertySymbols=="function"?t=>[...Object.getOwnPropertyNames(t),...Object.getOwnPropertySymbols(t)]:Object.getOwnPropertyNames,Yc=(()=>{function t(r){function n(){return Reflect.construct(r,arguments,new.target)}return n.prototype=Object.create(r.prototype,{constructor:{value:n}}),Reflect.setPrototypeOf(n,r),n}function e(){let n=t(function(){this.a.call(this)});return n.prototype.a=function(){},new n}try{return e(),t}catch{return n=>class extends n{}}})();function Wc(t){return{identifier:t.identifier,controllerConstructor:jc(t.controllerConstructor)}}var ii=class{constructor(e,r){this.application=e,this.definition=Wc(r),this.contextsByScope=new WeakMap,this.connectedContexts=new Set}get identifier(){return this.definition.identifier}get controllerConstructor(){return this.definition.controllerConstructor}get contexts(){return Array.from(this.connectedContexts)}connectContextForScope(e){let r=this.fetchContextForScope(e);this.connectedContexts.add(r),r.connect()}disconnectContextForScope(e){let r=this.contextsByScope.get(e);r&&(this.connectedContexts.delete(r),r.disconnect())}fetchContextForScope(e){let r=this.contextsByScope.get(e);return r||(r=new ni(this,e),this.contextsByScope.set(e,r)),r}},si=class{constructor(e){this.scope=e}has(e){return this.data.has(this.getDataKey(e))}get(e){return this.getAll(e)[0]}getAll(e){let r=this.data.get(this.getDataKey(e))||"";return Sc(r)}getAttributeName(e){return this.data.getAttributeNameForKey(this.getDataKey(e))}getDataKey(e){return`${e}-class`}get data(){return this.scope.data}},oi=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get(e){let r=this.getAttributeNameForKey(e);return this.element.getAttribute(r)}set(e,r){let n=this.getAttributeNameForKey(e);return this.element.setAttribute(n,r),this.get(e)}has(e){let r=this.getAttributeNameForKey(e);return this.element.hasAttribute(r)}delete(e){if(this.has(e)){let r=this.getAttributeNameForKey(e);return this.element.removeAttribute(r),!0}else return!1}getAttributeNameForKey(e){return`data-${this.identifier}-${fo(e)}`}},ai=class{constructor(e){this.warnedKeysByObject=new WeakMap,this.logger=e}warn(e,r,n){let i=this.warnedKeysByObject.get(e);i||(i=new Set,this.warnedKeysByObject.set(e,i)),i.has(r)||(i.add(r),this.logger.warn(n,e))}};function li(t,e){return`[${t}~="${e}"]`}var ci=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((r,n)=>r||this.findTarget(n)||this.findLegacyTarget(n),void 0)}findAll(...e){return e.reduce((r,n)=>[...r,...this.findAllTargets(n),...this.findAllLegacyTargets(n)],[])}findTarget(e){let r=this.getSelectorForTargetName(e);return this.scope.findElement(r)}findAllTargets(e){let r=this.getSelectorForTargetName(e);return this.scope.findAllElements(r)}getSelectorForTargetName(e){let r=this.schema.targetAttributeForScope(this.identifier);return li(r,e)}findLegacyTarget(e){let r=this.getLegacySelectorForTargetName(e);return this.deprecate(this.scope.findElement(r),e)}findAllLegacyTargets(e){let r=this.getLegacySelectorForTargetName(e);return this.scope.findAllElements(r).map(n=>this.deprecate(n,e))}getLegacySelectorForTargetName(e){let r=`${this.identifier}.${e}`;return li(this.schema.targetAttribute,r)}deprecate(e,r){if(e){let{identifier:n}=this,i=this.schema.targetAttribute,s=this.schema.targetAttributeForScope(n);this.guide.warn(e,`target:${r}`,`Please replace ${i}="${n}.${r}" with ${s}="${r}". The ${i} attribute is deprecated and will be removed in a future version of Stimulus.`)}return e}get guide(){return this.scope.guide}},ui=class{constructor(e,r){this.scope=e,this.controllerElement=r}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((r,n)=>r||this.findOutlet(n),void 0)}findAll(...e){return e.reduce((r,n)=>[...r,...this.findAllOutlets(n)],[])}getSelectorForOutletName(e){let r=this.schema.outletAttributeForScope(this.identifier,e);return this.controllerElement.getAttribute(r)}findOutlet(e){let r=this.getSelectorForOutletName(e);if(r)return this.findElement(r,e)}findAllOutlets(e){let r=this.getSelectorForOutletName(e);return r?this.findAllElements(r,e):[]}findElement(e,r){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,r))[0]}findAllElements(e,r){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,r))}matchesElement(e,r,n){let i=e.getAttribute(this.scope.schema.controllerAttribute)||"";return e.matches(r)&&i.split(" ").includes(n)}},hi=class t{constructor(e,r,n,i){this.targets=new ci(this),this.classes=new si(this),this.data=new oi(this),this.containsElement=s=>s.closest(this.controllerSelector)===this.element,this.schema=e,this.element=r,this.identifier=n,this.guide=new ai(i),this.outlets=new ui(this.documentScope,r)}findElement(e){return this.element.matches(e)?this.element:this.queryElements(e).find(this.containsElement)}findAllElements(e){return[...this.element.matches(e)?[this.element]:[],...this.queryElements(e).filter(this.containsElement)]}queryElements(e){return Array.from(this.element.querySelectorAll(e))}get controllerSelector(){return li(this.schema.controllerAttribute,this.identifier)}get isDocumentScope(){return this.element===document.documentElement}get documentScope(){return this.isDocumentScope?this:new t(this.schema,document.documentElement,this.identifier,this.guide.logger)}},fi=class{constructor(e,r,n){this.element=e,this.schema=r,this.delegate=n,this.valueListObserver=new Ur(this.element,this.controllerAttribute,this),this.scopesByIdentifierByElement=new WeakMap,this.scopeReferenceCounts=new WeakMap}start(){this.valueListObserver.start()}stop(){this.valueListObserver.stop()}get controllerAttribute(){return this.schema.controllerAttribute}parseValueForToken(e){let{element:r,content:n}=e;return this.parseValueForElementAndIdentifier(r,n)}parseValueForElementAndIdentifier(e,r){let n=this.fetchScopesByIdentifierForElement(e),i=n.get(r);return i||(i=this.delegate.createScopeForElementAndIdentifier(e,r),n.set(r,i)),i}elementMatchedValue(e,r){let n=(this.scopeReferenceCounts.get(r)||0)+1;this.scopeReferenceCounts.set(r,n),n==1&&this.delegate.scopeConnected(r)}elementUnmatchedValue(e,r){let n=this.scopeReferenceCounts.get(r);n&&(this.scopeReferenceCounts.set(r,n-1),n==1&&this.delegate.scopeDisconnected(r))}fetchScopesByIdentifierForElement(e){let r=this.scopesByIdentifierByElement.get(e);return r||(r=new Map,this.scopesByIdentifierByElement.set(e,r)),r}},di=class{constructor(e){this.application=e,this.scopeObserver=new fi(this.element,this.schema,this),this.scopesByIdentifier=new at,this.modulesByIdentifier=new Map}get element(){return this.application.element}get schema(){return this.application.schema}get logger(){return this.application.logger}get controllerAttribute(){return this.schema.controllerAttribute}get modules(){return Array.from(this.modulesByIdentifier.values())}get contexts(){return this.modules.reduce((e,r)=>e.concat(r.contexts),[])}start(){this.scopeObserver.start()}stop(){this.scopeObserver.stop()}loadDefinition(e){this.unloadIdentifier(e.identifier);let r=new ii(this.application,e);this.connectModule(r);let n=e.controllerConstructor.afterLoad;n&&n.call(e.controllerConstructor,e.identifier,this.application)}unloadIdentifier(e){let r=this.modulesByIdentifier.get(e);r&&this.disconnectModule(r)}getContextForElementAndIdentifier(e,r){let n=this.modulesByIdentifier.get(r);if(n)return n.contexts.find(i=>i.element==e)}proposeToConnectScopeForElementAndIdentifier(e,r){let n=this.scopeObserver.parseValueForElementAndIdentifier(e,r);n?this.scopeObserver.elementMatchedValue(n.element,n):console.error(`Couldn't find or create scope for identifier: "${r}" and element:`,e)}handleError(e,r,n){this.application.handleError(e,r,n)}createScopeForElementAndIdentifier(e,r){return new hi(this.schema,e,r,this.logger)}scopeConnected(e){this.scopesByIdentifier.add(e.identifier,e);let r=this.modulesByIdentifier.get(e.identifier);r&&r.connectContextForScope(e)}scopeDisconnected(e){this.scopesByIdentifier.delete(e.identifier,e);let r=this.modulesByIdentifier.get(e.identifier);r&&r.disconnectContextForScope(e)}connectModule(e){this.modulesByIdentifier.set(e.identifier,e),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(n=>e.connectContextForScope(n))}disconnectModule(e){this.modulesByIdentifier.delete(e.identifier),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(n=>e.disconnectContextForScope(n))}},Gc={controllerAttribute:"data-controller",actionAttribute:"data-action",targetAttribute:"data-target",targetAttributeForScope:t=>`data-${t}-target`,outletAttributeForScope:(t,e)=>`data-${t}-${e}-outlet`,keyMappings:Object.assign(Object.assign({enter:"Enter",tab:"Tab",esc:"Escape",space:" ",up:"ArrowUp",down:"ArrowDown",left:"ArrowLeft",right:"ArrowRight",home:"Home",end:"End",page_up:"PageUp",page_down:"PageDown"},oo("abcdefghijklmnopqrstuvwxyz".split("").map(t=>[t,t]))),oo("0123456789".split("").map(t=>[t,t])))};function oo(t){return t.reduce((e,[r,n])=>Object.assign(Object.assign({},e),{[r]:n}),{})}var Kr=class{constructor(e=document.documentElement,r=Gc){this.logger=console,this.debug=!1,this.logDebugActivity=(n,i,s={})=>{this.debug&&this.logFormattedMessage(n,i,s)},this.element=e,this.schema=r,this.dispatcher=new qn(this),this.router=new di(this),this.actionDescriptorFilters=Object.assign({},kc)}static start(e,r){let n=new this(e,r);return n.start(),n}async start(){await Jc(),this.logDebugActivity("application","starting"),this.dispatcher.start(),this.router.start(),this.logDebugActivity("application","start")}stop(){this.logDebugActivity("application","stopping"),this.dispatcher.stop(),this.router.stop(),this.logDebugActivity("application","stop")}register(e,r){this.load({identifier:e,controllerConstructor:r})}registerActionOption(e,r){this.actionDescriptorFilters[e]=r}load(e,...r){(Array.isArray(e)?e:[e,...r]).forEach(i=>{i.controllerConstructor.shouldLoad&&this.router.loadDefinition(i)})}unload(e,...r){(Array.isArray(e)?e:[e,...r]).forEach(i=>this.router.unloadIdentifier(i))}get controllers(){return this.router.contexts.map(e=>e.controller)}getControllerForElementAndIdentifier(e,r){let n=this.router.getContextForElementAndIdentifier(e,r);return n?n.controller:null}handleError(e,r,n){var i;this.logger.error(`%s

%o

%o`,r,e,n),(i=window.onerror)===null||i===void 0||i.call(window,r,"",0,0,e)}logFormattedMessage(e,r,n={}){n=Object.assign({application:this},n),this.logger.groupCollapsed(`${e} #${r}`),this.logger.log("details:",Object.assign({},n)),this.logger.groupEnd()}};function Jc(){return new Promise(t=>{document.readyState=="loading"?document.addEventListener("DOMContentLoaded",()=>t()):t()})}function Zc(t){return gr(t,"classes").reduce((r,n)=>Object.assign(r,Xc(n)),{})}function Xc(t){return{[`${t}Class`]:{get(){let{classes:e}=this;if(e.has(t))return e.get(t);{let r=e.getAttributeName(t);throw new Error(`Missing attribute "${r}"`)}}},[`${t}Classes`]:{get(){return this.classes.getAll(t)}},[`has${vr(t)}Class`]:{get(){return this.classes.has(t)}}}}function Qc(t){return gr(t,"outlets").reduce((r,n)=>Object.assign(r,eu(n)),{})}function ao(t,e,r){return t.application.getControllerForElementAndIdentifier(e,r)}function lo(t,e,r){let n=ao(t,e,r);if(n||(t.application.router.proposeToConnectScopeForElementAndIdentifier(e,r),n=ao(t,e,r),n))return n}function eu(t){let e=Yn(t);return{[`${e}Outlet`]:{get(){let r=this.outlets.find(t),n=this.outlets.getSelectorForOutletName(t);if(r){let i=lo(this,r,t);if(i)return i;throw new Error(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`)}throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${n}".`)}},[`${e}Outlets`]:{get(){let r=this.outlets.findAll(t);return r.length>0?r.map(n=>{let i=lo(this,n,t);if(i)return i;console.warn(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`,n)}).filter(n=>n):[]}},[`${e}OutletElement`]:{get(){let r=this.outlets.find(t),n=this.outlets.getSelectorForOutletName(t);if(r)return r;throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${n}".`)}},[`${e}OutletElements`]:{get(){return this.outlets.findAll(t)}},[`has${vr(e)}Outlet`]:{get(){return this.outlets.has(t)}}}}function tu(t){return gr(t,"targets").reduce((r,n)=>Object.assign(r,ru(n)),{})}function ru(t){return{[`${t}Target`]:{get(){let e=this.targets.find(t);if(e)return e;throw new Error(`Missing target element "${t}" for "${this.identifier}" controller`)}},[`${t}Targets`]:{get(){return this.targets.findAll(t)}},[`has${vr(t)}Target`]:{get(){return this.targets.has(t)}}}}function nu(t){let e=Bc(t,"values"),r={valueDescriptorMap:{get(){return e.reduce((n,i)=>{let s=go(i,this.identifier),a=this.data.getAttributeNameForKey(s.key);return Object.assign(n,{[a]:s})},{})}}};return e.reduce((n,i)=>Object.assign(n,iu(i)),r)}function iu(t,e){let r=go(t,e),{key:n,name:i,reader:s,writer:a}=r;return{[i]:{get(){let l=this.data.get(n);return l!==null?s(l):r.defaultValue},set(l){l===void 0?this.data.delete(n):this.data.set(n,a(l))}},[`has${vr(i)}`]:{get(){return this.data.has(n)||r.hasCustomDefaultValue}}}}function go([t,e],r){return lu({controller:r,token:t,typeDefinition:e})}function zr(t){switch(t){case Array:return"array";case Boolean:return"boolean";case Number:return"number";case Object:return"object";case String:return"string"}}function pr(t){switch(typeof t){case"boolean":return"boolean";case"number":return"number";case"string":return"string"}if(Array.isArray(t))return"array";if(Object.prototype.toString.call(t)==="[object Object]")return"object"}function su(t){let{controller:e,token:r,typeObject:n}=t,i=no(n.type),s=no(n.default),a=i&&s,l=i&&!s,c=!i&&s,u=zr(n.type),f=pr(t.typeObject.default);if(l)return u;if(c)return f;if(u!==f){let v=e?`${e}.${r}`:r;throw new Error(`The specified default value for the Stimulus Value "${v}" must match the defined type "${u}". The provided default value of "${n.default}" is of type "${f}".`)}if(a)return u}function ou(t){let{controller:e,token:r,typeDefinition:n}=t,s=su({controller:e,token:r,typeObject:n}),a=pr(n),l=zr(n),c=s||a||l;if(c)return c;let u=e?`${e}.${n}`:r;throw new Error(`Unknown value type "${u}" for "${r}" value`)}function au(t){let e=zr(t);if(e)return co[e];let r=Wn(t,"default"),n=Wn(t,"type"),i=t;if(r)return i.default;if(n){let{type:s}=i,a=zr(s);if(a)return co[a]}return t}function lu(t){let{token:e,typeDefinition:r}=t,n=`${fo(e)}-value`,i=ou(t);return{type:i,key:n,name:pi(n),get defaultValue(){return au(r)},get hasCustomDefaultValue(){return pr(r)!==void 0},reader:cu[i],writer:uo[i]||uo.default}}var co={get array(){return[]},boolean:!1,number:0,get object(){return{}},string:""},cu={array(t){let e=JSON.parse(t);if(!Array.isArray(e))throw new TypeError(`expected value of type "array" but instead got value "${t}" of type "${pr(e)}"`);return e},boolean(t){return!(t=="0"||String(t).toLowerCase()=="false")},number(t){return Number(t.replace(/_/g,""))},object(t){let e=JSON.parse(t);if(e===null||typeof e!="object"||Array.isArray(e))throw new TypeError(`expected value of type "object" but instead got value "${t}" of type "${pr(e)}"`);return e},string(t){return t}},uo={default:uu,array:ho,object:ho};function ho(t){return JSON.stringify(t)}function uu(t){return`${t}`}var et=class{constructor(e){this.context=e}static get shouldLoad(){return!0}static afterLoad(e,r){}get application(){return this.context.application}get scope(){return this.context.scope}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get targets(){return this.scope.targets}get outlets(){return this.scope.outlets}get classes(){return this.scope.classes}get data(){return this.scope.data}initialize(){}connect(){}disconnect(){}dispatch(e,{target:r=this.element,detail:n={},prefix:i=this.identifier,bubbles:s=!0,cancelable:a=!0}={}){let l=i?`${i}:${e}`:e,c=new CustomEvent(l,{detail:n,bubbles:s,cancelable:a});return r.dispatchEvent(c),c}};et.blessings=[Zc,tu,nu,Qc];et.targets=[];et.outlets=[];et.values={};var vi=Kr.start();vi.register("navigation",class extends et{static get targets(){return["button"]}connect(){this.element.addEventListener("keydown",this.trapEscape.bind(this))}trapEscape(t){t.key==="Escape"&&this.close()}switch(){this.buttonTarget.getAttribute("aria-expanded")==="true"?this.buttonTarget.setAttribute("aria-expanded","false"):this.buttonTarget.setAttribute("aria-expanded","true")}close(){this.buttonTarget.setAttribute("aria-expanded","false"),this.buttonTarget.focus()}});vi.register("profile",class extends et{static get targets(){return["sectionNatural","sectionLegal","firstName","lastName","legalName","controlAddress"]}initialize(){this.switchAddressForNode(this.controlAddressTarget);let t=document.querySelector('input[name="entity_type"][checked]');this.switchEntityTypeForNode(t)}switchAddress(t){this.switchAddressForNode(t.target)}switchAddressForNode(t){let e=document.querySelector("#address");e.hidden=!t.checked}switchEntityType(t){this.switchEntityTypeForNode(t.target)}switchEntityTypeForNode(t){t.value==="natural"?(this.sectionNaturalTarget.hidden=!1,this.sectionLegalTarget.hidden=!0,this.firstNameTarget.required=!0,this.lastNameTarget.required=!0,this.legalNameTarget.required=!1,this.controlAddressTarget.checked=this.controlAddressTarget.defaultChecked,this.controlAddressTarget.hidden=!1):(this.sectionNaturalTarget.hidden=!0,this.sectionLegalTarget.hidden=!1,this.firstNameTarget.required=!1,this.lastNameTarget.required=!1,this.legalNameTarget.required=!0,this.controlAddressTarget.checked=!0,this.controlAddressTarget.hidden=!0),this.switchAddressForNode(this.controlAddressTarget)}});vi.register("amount-selector",class extends et{static get targets(){return["amount","radio","totalAmount"]}initialize(){let t=this.data.get("initialAmount");this.setAmount(t)}setAmount(t){let e=this.amountTarget;e.value=t,this.refreshTotalAmount()}select(t){let e=t.target;this.setAmount(e.dataset.value)}change(t){this.radioTargets.forEach(function(e){e.checked=!1}),this.refreshTotalAmount()}refreshTotalAmount(){if(this.totalAmountTarget){let t=parseInt(this.amountTarget.value,10),e=parseInt(this.data.get("countAccounts"),10);this.totalAmountTarget.innerHTML=t*e}}});})();
//# sourceMappingURL=application.js.map
