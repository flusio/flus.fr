(()=>{var Hr=Array.isArray,Na=Array.prototype.indexOf,Ln=Array.prototype.includes,Fa=Array.from,In=Object.keys,Ut=Object.defineProperty,Bt=Object.getOwnPropertyDescriptor,La=Object.getOwnPropertyDescriptors,Ia=Object.prototype,Da=Array.prototype,bs=Object.getPrototypeOf,Xi=Object.isExtensible,vt=()=>{};function Ra(t){for(var e=0;e<t.length;e++)t[e]()}function ys(){var t,e,n=new Promise((r,i)=>{t=r,e=i});return{promise:n,resolve:t,reject:e}}var de=2,Kt=4,Un=8,zr=1<<24,Be=16,Ke=32,rt=64,xr=128,qr=256,Ie=512,le=1024,ce=2048,Ue=4096,De=8192,Te=16384,Mt=32768,Dn=1<<25,mt=65536,Rn=1<<17,Pa=1<<18,Nt=1<<19,Ba=1<<20,St=65536,Pn=1<<21,Vt=1<<22,gt=1<<23,jt=Symbol("$state"),ws=Symbol("component"),Va=Symbol("legacy props"),ja=Symbol(""),_s=Symbol("attributes"),Cr=Symbol("class"),Or=Symbol("style"),Sr=Symbol("text"),ln=Symbol("form reset"),dn=new class extends Error{name="StaleReactionError";message="The reaction that called `getAbortSignal()` was re-run or destroyed"},pn=!!globalThis.document?.contentType&&globalThis.document.contentType.includes("xml"),vn=3,gn=8;function Es(t){return t===this.v}function ks(t,e){return t!=t?e==e:t!==e||t!==null&&typeof t=="object"||typeof t=="function"}function Ua(t){return!ks(t,this.v)}function Ka(t){throw new Error("https://svelte.dev/e/lifecycle_outside_component")}function Ha(){throw new Error("https://svelte.dev/e/async_derived_orphan")}function za(t){throw new Error("https://svelte.dev/e/effect_in_teardown")}function qa(){throw new Error("https://svelte.dev/e/effect_in_unowned_derived")}function Ya(t){throw new Error("https://svelte.dev/e/effect_orphan")}function Ga(){throw new Error("https://svelte.dev/e/effect_update_depth_exceeded")}function Wa(){throw new Error("https://svelte.dev/e/hydration_failed")}function Ja(){throw new Error("https://svelte.dev/e/state_descriptors_fixed")}function Xa(){throw new Error("https://svelte.dev/e/state_prototype_fixed")}function Za(){throw new Error("https://svelte.dev/e/state_unsafe_mutation")}function Qa(){throw new Error("https://svelte.dev/e/svelte_boundary_reset_onerror")}var el=1,tl=2,Yr="[",As="[!",Zi="[?",xs="]",Tt={},re=Symbol("uninitialized"),Cs="http://www.w3.org/1999/xhtml",nl="http://www.w3.org/2000/svg",rl="http://www.w3.org/1998/Math/MathML",il="@attach",be=null;function Ht(t){be=t}function ut(t,e=!1,n){be={p:be,i:!1,c:null,e:null,s:t,x:null,r:T,l:null}}function ht(t){var e=be,n=e.e;if(n!==null){e.e=null;for(var r of n)to(r)}return t!==void 0&&(e.x=t),e.i=!0,be=e.p,Gr(t)}function Gr(t={}){return Ut(t,ws,{value:!0}),t}function Os(){return!0}var kt=[];function Ss(){var t=kt;kt=[],Ra(t)}function Qe(t){if(kt.length===0&&!cn){var e=kt;queueMicrotask(()=>{e===kt&&Ss()})}kt.push(t)}function sl(){for(;kt.length>0;)Ss()}function ol(){console.warn("https://svelte.dev/e/derived_inert")}function mn(t){console.warn("https://svelte.dev/e/hydration_mismatch")}function al(){console.warn("https://svelte.dev/e/select_multiple_invalid_value")}function ll(){console.warn("https://svelte.dev/e/svelte_boundary_reset_noop")}var S=!1;function et(t){S=t}var D;function _e(t){if(t===null)throw mn(),Tt;return D=t}function $t(){return _e(st(D))}function ie(t){if(S){if(st(D)!==null)throw mn(),Tt;D=t}}function Wr(t=1){if(S){for(var e=t,n=D;e--;)n=st(n);D=n}}function Jr(t=!0){for(var e=0,n=D;;){if(n.nodeType===gn){var r=n.data;if(r===xs){if(e===0)return n;e-=1}else(r===Yr||r===As||r[0]==="["&&!isNaN(Number(r.slice(1))))&&(e+=1)}var i=st(n);t&&n.remove(),n=i}}function Ts(t){if(!t||t.nodeType!==gn)throw mn(),Tt;return t.data}function lt(t){if(typeof t!="object"||t===null||jt in t||ws in t)return t;let e=bs(t);if(e!==Ia&&e!==Da)return t;var n=new Map,r=Hr(t),i=M(0),s=Ot,a=l=>{if(Ot===s)return l();var u=F,c=Ot;Re(null),ss(s);var f=l();return Re(u),ss(c),f};return r&&n.set("length",M(t.length)),new Proxy(t,{defineProperty(l,u,c){(!("value"in c)||c.configurable===!1||c.enumerable===!1||c.writable===!1)&&Ja();var f=n.get(u);return f===void 0?a(()=>{var d=M(c.value);return n.set(u,d),d}):w(f,c.value,!0),!0},deleteProperty(l,u){var c=n.get(u);if(c===void 0){if(u in l){let f=a(()=>M(re));n.set(u,f),un(i)}}else w(c,re),un(i);return!0},get(l,u,c){if(u===jt)return t;var f=n.get(u),d=u in l;if(f===void 0&&(!d||Bt(l,u)?.writable)&&(f=a(()=>{var g=lt(d?l[u]:re),m=M(g);return m}),n.set(u,f)),f!==void 0){var v=o(f);return v===re?void 0:v}return Reflect.get(l,u,c)},getOwnPropertyDescriptor(l,u){var c=Reflect.getOwnPropertyDescriptor(l,u);if(c&&"value"in c){var f=n.get(u);f&&(c.value=o(f))}else if(c===void 0){var d=n.get(u),v=d?.v;if(d!==void 0&&v!==re)return{enumerable:!0,configurable:!0,value:v,writable:!0}}return c},has(l,u){if(u===jt)return!0;var c=n.get(u),f=c!==void 0&&c.v!==re||Reflect.has(l,u);if(c!==void 0||T!==null&&(!f||Bt(l,u)?.writable)){c===void 0&&(c=a(()=>{var v=f?lt(l[u]):re,g=M(v);return g}),n.set(u,c));var d=o(c);if(d===re)return!1}return f},set(l,u,c,f){var d=n.get(u),v=u in l;if(r&&u==="length")for(var g=c;g<d.v;g+=1){var m=n.get(g+"");m!==void 0?w(m,re):g in l&&(m=a(()=>M(re)),n.set(g+"",m))}if(d===void 0)(!v||Bt(l,u)?.writable)&&(d=a(()=>M(void 0)),w(d,lt(c)),n.set(u,d));else{v=d.v!==re;var k=a(()=>lt(c));w(d,k)}var N=Reflect.getOwnPropertyDescriptor(l,u);if(N?.set&&N.set.call(f,c),!v){if(r&&typeof u=="string"){var H=n.get("length"),V=Number(u);Number.isInteger(V)&&V>=H.v&&w(H,V+1)}un(i)}return!0},ownKeys(l){o(i);var u=Reflect.ownKeys(l).filter(d=>{var v=n.get(d);return v===void 0||v.v!==re});for(var[c,f]of n)f.v!==re&&!(c in l)&&u.push(c);return u},setPrototypeOf(){Xa()}})}function Qi(t){try{if(t!==null&&typeof t=="object"&&jt in t)return t[jt]}catch{}return t}function $s(t,e){return Object.is(Qi(t),Qi(e))}var Ct,Tr,Ms,Ns,Fs;function $r(){if(Ct===void 0){Ct=window,Tr=document,Ms=/Firefox/.test(navigator.userAgent);var t=Element.prototype,e=Node.prototype,n=Text.prototype;Ns=Bt(e,"firstChild").get,Fs=Bt(e,"nextSibling").get,Xi(t)&&(t[Cr]=void 0,t[_s]=null,t[Or]=void 0,t.__e=void 0),Xi(n)&&(n[Sr]=void 0)}}function He(t=""){return document.createTextNode(t)}function Ae(t){return Ns.call(t)}function st(t){return Fs.call(t)}function fe(t,e){if(!S)return Ae(t);var n=Ae(D);if(n===null)n=D.appendChild(He());else if(e&&n.nodeType!==vn){var r=He();return n?.before(r),_e(r),r}return e&&Kn(n),_e(n),n}function Dt(t,e=!1){if(!S){var n=Ae(t);return n instanceof Comment&&n.data===""?st(n):n}if(e){if(D?.nodeType!==vn){var r=He();return D?.before(r),_e(r),r}Kn(D)}return D}function Rt(t,e=!1){if(!S)return Ae(t);var n=fe(t,e);return ie(t),n}function J(t,e=1,n=!1){let r=S?D:t;for(var i;e--;)i=r,r=st(r);if(!S)return r;if(n){if(r?.nodeType!==vn){var s=He();return r===null?i?.after(s):r.before(s),_e(s),s}Kn(r)}return _e(r),r}function cl(t){t.textContent=""}function ul(){return!1}function Xr(t,e,n){return e==null||e===Cs?n?document.createElement(t,{is:n}):document.createElement(t):n?document.createElementNS(e,t,{is:n}):document.createElementNS(e,t)}function Kn(t){if(t.nodeValue.length<65536)return;let e=t.nextSibling;for(;e!==null&&e.nodeType===vn;)e.remove(),t.nodeValue+=e.nodeValue,e=t.nextSibling}function hl(t){var e=T;if(e===null)return F.f|=gt,t;if((e.f&Mt)===0&&(e.f&Kt)===0)throw t;Ze(t,e)}function Ze(t,e){if(!(e!==null&&(e.f&Te)!==0)){for(;e!==null;){if((e.f&xr)!==0&&(e.f&(Te|Dn))===0){if((e.f&Mt)===0)throw t;try{e.b.error(t);return}catch(n){t=n}}e=e.parent}throw t}}var fl=-7169;function te(t,e){t.f=t.f&fl|e}function Zr(t){(t.f&Ie)!==0||t.deps===null?te(t,le):te(t,Ue)}function Ls(t){if(t!==null)for(let e of t)(e.f&de)===0||(e.f&St)===0||(e.f^=St,Ls(e.deps))}function Is(t,e,n){(t.f&ce)!==0?e.add(t):(t.f&Ue)!==0&&n.add(t),Ls(t.deps),te(t,le)}function Ds(t,e,n){if(t==null)return e(void 0),vt;let r=wn(()=>t.subscribe(e,n));return r.unsubscribe?()=>r.unsubscribe():r}var Lt=[];function dl(t,e=vt){let n=null,r=new Set;function i(l){if(ks(t,l)&&(t=l,n)){let u=!Lt.length;for(let c of r)c[1](),Lt.push(c,t);if(u){for(let c=0;c<Lt.length;c+=2)Lt[c][0](Lt[c+1]);Lt.length=0}}}function s(l){i(l(t))}function a(l,u=vt){let c=[l,u];return r.add(c),r.size===1&&(n=e(i,s)||vt),l(t),()=>{r.delete(c),r.size===0&&n&&(n(),n=null)}}return{set:i,update:s,subscribe:a}}function sn(t){let e;return Ds(t,n=>e=n)(),e}var Mr=Symbol("unmounted");function es(t,e,n){let r=n[e]??={store:null,source:qs(void 0),unsubscribe:vt};if(r.store!==t&&!(Mr in n))if(r.unsubscribe(),r.store=t??null,t==null)r.source.v=void 0,r.unsubscribe=vt;else{var i=!0;r.unsubscribe=Ds(t,s=>{i?r.source.v=s:w(r.source,s)}),i=!1}return t&&Mr in n?sn(t):o(r.source)}function pl(){let t={};function e(){Hn(()=>{for(var n in t)t[n].unsubscribe();Ut(t,Mr,{enumerable:!1,value:!0})})}return[t,e]}function vl(t,e){if(e){let n=document.body;t.autofocus=!0,Qe(()=>{document.activeElement===n&&t.focus()})}}var ts=!1;function Rs(){ts||(ts=!0,document.addEventListener("reset",t=>{Promise.resolve().then(()=>{if(!t.defaultPrevented)for(let e of t.target.elements)e[ln]?.()})},{capture:!0}))}function Yt(t){var e=F,n=T;Re(null),it(null);try{return t()}finally{Re(e),it(n)}}function gl(t,e,n,r=n){t.addEventListener(e,()=>Yt(n));let i=t[ln];i?t[ln]=()=>{i(),r(!0)}:t[ln]=()=>r(!0),Rs()}function Ps(t,e,n,r){let i=Qr;var s=t.filter(g=>!g.settled),a=e.map(i);if(n.length===0&&s.length===0){r(a);return}var l=T,u=ml(),c=s.length===1?s[0].promise:s.length>1?Promise.all(s.map(g=>g.promise)):null;function f(g){if((l.f&Te)===0){u();try{r([...a,...g])}catch(m){Ze(m,l)}Bn()}}var d=Bs();if(n.length===0){c.then(()=>f([])).finally(d);return}function v(){Promise.all(n.map(g=>bl(g))).then(f).catch(g=>Ze(g,l)).finally(d)}c?c.then(()=>{u(),v(),Bn()}):v()}function ml(){var t=T,e=F,n=be,r=$;return function(s=!0){it(t),Re(e),Ht(n),s&&(t.f&Te)===0&&(r?.activate(),r?.apply())}}function Bn(t=!0){it(null),Re(null),Ht(null),t&&$?.deactivate()}function Bs(){var t=T,e=t.b,n=$,r=!!e?.is_rendered();return e?.update_pending_count(1,n),n.increment(r,t),()=>{e?.update_pending_count(-1,n),n.decrement(r,t)}}function Qr(t){var e=de|ce;return T!==null&&(T.f|=Nt),{ctx:be,deps:null,effects:null,equals:Es,f:e,fn:t,reactions:null,rv:0,v:re,wv:0,parent:T,ac:null}}var on=Symbol("obsolete");function bl(t,e,n){let r=T;r===null&&Ha();var i=void 0,s=bn(re),a=!F,l=new Set;return $l(()=>{var u=T,c=ys();i=c.promise;try{Promise.resolve(t()).then(c.resolve,g=>{g!==dn&&c.reject(g)}).finally(Bn)}catch(g){c.reject(g),Bn()}var f=$;if(a){if((u.f&Mt)!==0)var d=Bs();if(r.b?.is_rendered())f.async_deriveds.get(u)?.reject(on);else for(let g of l.values())g.reject(on);l.add(c),f.async_deriveds.set(u,c)}let v=(g,m=void 0)=>{d?.(),l.delete(c),m!==on&&(f.activate(),m?(s.f|=gt,jn(s,m)):((s.f&gt)!==0&&(s.f^=gt),jn(s,g)),f.deactivate())};c.promise.then(v,g=>v(null,g||"unknown"))}),Hn(()=>{for(let u of l)u.reject(on)}),new Promise(u=>{function c(f){function d(){f===i?u(s):c(i)}f.then(d,d)}c(i)})}function we(t){let e=Qr(t);return Gs(e),e}function yl(t){var e=t.effects;if(e!==null){t.effects=null;for(var n=0;n<e.length;n+=1)ve(e[n])}}function ei(t){var e,n=T,r=t.parent;if(!ct&&r!==null&&t.v!==re&&(r.f&(Te|De))!==0)return ol(),t.v;it(r);try{t.f&=~St,yl(t),e=Zs(t)}finally{it(n)}return e}function Vs(t){var e=ei(t);if(!t.equals(e)&&(t.wv=Js(),(!$?.is_fork||t.deps===null)&&($!==null?($.capture(t,e,!0),Nr?.capture(t,e,!0)):t.v=e,t.deps===null))){te(t,le);return}ct||(Ve!==null?(ri()||$?.is_fork)&&Ve.set(t,e):Zr(t))}function wl(t){if(t.effects!==null)for(let e of t.effects)(e.teardown||e.ac)&&(e.teardown?.(),e.ac!==null&&Yt(()=>{e.ac.abort(dn),e.ac=null}),e.fn!==null&&(e.teardown=vt),fn(e,0),si(e))}function js(t){if(t.effects!==null)for(let e of t.effects)e.teardown&&e.fn!==null&&zt(e)}var gr=null,It=null,$=null,Nr=null,Ve=null,Fr=null,cn=!1,mr=!1,Pt=null,Mn=null,ns=0;var _l=1,bt=class t{id=_l++;#e=!1;linked=!0;#t=null;#n=null;async_deriveds=new Map;current=new Map;previous=new Map;#l=new Set;#i=new Set;#o=0;#r=new Map;#a=null;#s=[];#v=[];#c=new Set;#u=new Set;#f=new Map;#g=new Set;is_fork=!1;#h=!1;constructor(){It===null?gr=It=this:(It.#n=this,this.#t=It),It=this}#y(){if(this.is_fork)return!0;for(let r of this.#r.keys()){for(var e=r,n=!1;e.parent!==null;){if(this.#f.has(e)){n=!0;break}e=e.parent}if(!n)return!0}return!1}skip_effect(e){this.#f.has(e)||this.#f.set(e,{d:[],m:[]}),this.#g.delete(e)}unskip_effect(e,n=r=>this.schedule(r)){var r=this.#f.get(e);if(r){this.#f.delete(e);for(var i of r.d)te(i,ce),n(i);for(i of r.m)te(i,Ue),n(i)}this.#g.add(e)}#m(){this.#e=!0,ns++>1e3&&(this.#p(),El());for(let u of this.#c)this.#u.delete(u),te(u,ce),this.schedule(u);for(let u of this.#u)te(u,Ue),this.schedule(u);let e=this.#s;this.#s=[],this.apply();var n=Pt=[],r=[],i=Mn=[];for(let u of e)try{this.#w(u,n,r)}catch(c){throw Hs(u),this.#y()||this.discard(),c}if($=null,i.length>0){var s=t.ensure();for(let u of i)s.schedule(u)}if(Pt=null,Mn=null,this.#y()){this.#d(r),this.#d(n);for(let[u,c]of this.#f)Ks(u,c);i.length>0&&$.#m();return}let a=this.#_();if(a){this.#d(r),this.#d(n),a.#E(this);return}this.#c.clear(),this.#u.clear();for(let u of this.#l)u(this);this.#l.clear(),Nr=this,rs(r),rs(n),Nr=null,this.#a?.resolve();var l=$;if(this.#o===0&&(this.#s.length===0||l!==null)&&this.#p(),this.#s.length>0)if(l!==null){let u=l;u.#s.push(...this.#s.filter(c=>!u.#s.includes(c)))}else l=this;l!==null&&(tt.clear(),l.#m())}#w(e,n,r){e.f^=le;for(var i=e.first;i!==null;){var s=i.f,a=(s&(Ke|rt))!==0,l=a&&(s&le)!==0,u=l||(s&De)!==0||this.#f.has(i);if(!u&&i.fn!==null){a?i.f^=le:(s&Kt)!==0?n.push(i):yn(i)&&((s&Be)!==0&&this.#u.add(i),zt(i));var c=i.first;if(c!==null){i=c;continue}}for(;i!==null;){var f=i.next;if(f!==null){i=f;break}i=i.parent}}}#_(){for(var e=this.#t;e!==null;){if(!e.is_fork){for(let[n,[,r]]of this.current)if(e.current.has(n)&&!r)return e}e=e.#t}return null}#E(e){for(let[r,i]of e.current)!this.previous.has(r)&&e.previous.has(r)&&this.previous.set(r,e.previous.get(r)),this.current.set(r,i);for(let[r,i]of e.async_deriveds){let s=this.async_deriveds.get(r);s&&i.promise.then(s.resolve).catch(s.reject)}e.async_deriveds.clear(),this.transfer_effects(e.#c,e.#u);let n=r=>{var i=r.reactions;if(i!==null&&!((r.f&de)!==0&&(r.f&(ce|Ue))===0))for(let l of i){var s=l.f;if((s&de)!==0)n(l);else{var a=l;s&(Vt|Be)&&!this.async_deriveds.has(a)&&(this.#u.delete(a),te(a,ce),this.schedule(a))}}};for(let r of this.current.keys())n(r);this.oncommit(()=>e.discard()),e.#p(),$=this,this.#m()}#d(e){for(var n=0;n<e.length;n+=1)Is(e[n],this.#c,this.#u)}capture(e,n,r=!1){e.v!==re&&!this.previous.has(e)&&this.previous.set(e,e.v),(e.f&gt)===0&&(this.current.set(e,[n,r]),Ve?.set(e,n)),this.is_fork||(e.v=n)}activate(){$=this}deactivate(){$=null,Ve=null}flush(){try{mr=!0,$=this,this.#m()}finally{ns=0,Fr=null,Pt=null,Mn=null,mr=!1,$=null,Ve=null,tt.clear()}}discard(){for(let e of this.#i)e(this);this.#i.clear();for(let e of this.async_deriveds.values())e.reject(on);this.#p(),this.#a?.resolve()}register_created_effect(e){this.#v.push(e)}#b(){for(let d=gr;d!==null;d=d.#n){var e=d.id<this.id,n=[];for(let[v,[g,m]]of this.current){if(d.current.has(v)){var r=d.current.get(v)[0];if(e&&g!==r)d.current.set(v,[g,m]);else continue}n.push(v)}if(e)for(let[v,g]of this.async_deriveds){let m=d.async_deriveds.get(v);m&&g.promise.then(m.resolve).catch(m.reject)}var i=[...d.current.keys()].filter(v=>!d.current.get(v)[1]);if(!(!d.#e||i.length===0)){var s=i.filter(v=>!this.current.has(v));if(s.length===0)e&&d.discard();else if(n.length>0){if(e)for(let v of this.#g)d.unskip_effect(v,g=>{(g.f&(Be|Vt))!==0?d.schedule(g):d.#d([g])});d.activate();var a=new Set,l=new Map;for(var u of n)Us(u,s,a,l);l=new Map;var c=[...d.current].filter(([v,g])=>{let m=this.current.get(v);return m?m[0]!==g[0]||m[1]!==g[1]:!0}).map(([v])=>v);if(c.length>0)for(let v of this.#v)(v.f&(Te|De|Rn))===0&&ti(v,c,l)&&((v.f&(Vt|Be))!==0?(te(v,ce),d.schedule(v)):d.#c.add(v));if(d.#s.length>0&&!d.#h){d.apply();for(var f of d.#s)d.#w(f,[],[]);d.#s=[]}d.deactivate()}}}}increment(e,n){if(this.#o+=1,e){let r=this.#r.get(n)??0;this.#r.set(n,r+1)}}decrement(e,n){if(this.#o-=1,e){let r=this.#r.get(n)??0;r===1?this.#r.delete(n):this.#r.set(n,r-1)}this.#h||(this.#h=!0,Qe(()=>{this.#h=!1,this.linked&&this.flush()}))}transfer_effects(e,n){for(let r of e)this.#c.add(r);for(let r of n)this.#u.add(r);e.clear(),n.clear()}oncommit(e){this.#l.add(e)}ondiscard(e){this.#i.add(e)}settled(){return(this.#a??=ys()).promise}static ensure(){if($===null){let e=$=new t;!mr&&!cn&&Qe(()=>{e.#e||e.flush()})}return $}apply(){{Ve=null;return}}schedule(e){if(Fr=e,e.b?.is_pending&&(e.f&(Kt|Un|zr))!==0&&(e.f&Mt)===0){e.b.defer_effect(e);return}for(var n=e;n.parent!==null;){n=n.parent;var r=n.f;if(Pt!==null&&n===T&&(F===null||(F.f&de)===0))return;if((r&(rt|Ke))!==0){if((r&le)===0)return;n.f^=le}}this.#s.push(n)}#p(){if(this.linked){var e=this.#t,n=this.#n;e===null?gr=n:e.#n=n,n===null?It=e:n.#t=e,this.linked=!1}}};function q(t){var e=cn;cn=!0;try{for(var n;;){if(sl(),$===null)return n;$.flush()}}finally{cn=e}}function El(){try{Ga()}catch(t){Ze(t,Fr)}}var at=null;function rs(t){var e=t.length;if(e!==0){for(var n=0;n<e;){var r=t[n++];if((r.f&(Te|De))===0&&yn(r)&&(at=new Set,zt(r),r.deps===null&&r.first===null&&r.nodes===null&&r.teardown===null&&r.ac===null&&so(r),at?.size>0)){tt.clear();for(let i of at){if((i.f&(Te|De))!==0)continue;let s=[i],a=i.parent;for(;a!==null;)at.has(a)&&(at.delete(a),s.push(a)),a=a.parent;for(let l=s.length-1;l>=0;l--){let u=s[l];(u.f&(Te|De))===0&&zt(u)}}at.clear()}}at=null}}function Us(t,e,n,r){if(!n.has(t)&&(n.add(t),t.reactions!==null))for(let i of t.reactions){let s=i.f;(s&de)!==0?Us(i,e,n,r):(s&(Vt|Be))!==0&&(s&ce)===0&&ti(i,e,r)&&(te(i,ce),ni(i))}}function ti(t,e,n){let r=n.get(t);if(r!==void 0)return r;if(t.deps!==null)for(let i of t.deps){if(Ln.call(e,i))return!0;if((i.f&de)!==0&&ti(i,e,n))return n.set(i,!0),!0}return n.set(t,!1),!1}function ni(t){$.schedule(t)}function Ks(t,e){if(!((t.f&Ke)!==0&&(t.f&le)!==0)){(t.f&ce)!==0?e.d.push(t):(t.f&Ue)!==0&&e.m.push(t),te(t,le);for(var n=t.first;n!==null;)Ks(n,e),n=n.next}}function Hs(t){te(t,le);for(var e=t.first;e!==null;)Hs(e),e=e.next}var Vn=new Set,tt=new Map,zs=!1;function bn(t,e){var n={f:0,v:t,reactions:null,equals:Es,rv:0,wv:0};return n}function M(t,e){let n=bn(t);return Gs(n),n}function qs(t,e=!1,n=!0){let r=bn(t);return e||(r.equals=Ua),r}function w(t,e,n=!1){F!==null&&(!je||(F.f&Rn)!==0)&&Os()&&(F.f&(de|Be|Vt|Rn))!==0&&(nt===null||!nt.has(t))&&Za();let r=n?lt(e):e;return jn(t,r,Mn)}function jn(t,e,n=null){if(!t.equals(e)){ct?tt.set(t,e):tt.has(t)||tt.set(t,t.v);var r=bt.ensure();if(r.capture(t,e),(t.f&de)!==0){let i=t;(t.f&ce)!==0&&ei(i),Ve===null&&Zr(i)}t.wv=Js(),Ys(t,ce,n),T!==null&&(T.f&le)!==0&&(T.f&(Ke|rt))===0&&(Le===null?Al([t]):Le.push(t)),!r.is_fork&&Vn.size>0&&!zs&&kl()}return e}function kl(){zs=!1;for(let t of Vn){(t.f&le)!==0&&te(t,Ue);let e;try{e=yn(t)}catch{e=!0}e&&zt(t)}Vn.clear()}function un(t){w(t,t.v+1)}function Ys(t,e,n){var r=t.reactions;if(r!==null)for(var i=r.length,s=0;s<i;s++){var a=r[s],l=a.f,u=(l&ce)===0;if(u&&te(a,e),(l&Rn)!==0)Vn.add(a);else if((l&de)!==0){var c=a;Ve?.delete(c),(l&St)===0&&(l&Ie&&(T===null||(T.f&Pn)===0)&&(a.f|=St),Ys(c,Ue,n))}else if(u){var f=a;(l&Be)!==0&&at!==null&&at.add(f),n!==null?n.push(f):ni(f)}}}var Nn=!1,ct=!1;function is(t){ct=t}var F=null,je=!1;function Re(t){F=t}var T=null;function it(t){T=t}var nt=null;function Gs(t){F!==null&&(nt??=new Set).add(t)}var xe=null,Se=0,Le=null;function Al(t){Le=t}var Ws=1,At=0,Ot=At;function ss(t){Ot=t}function Js(){return++Ws}function yn(t){var e=t.f;if((e&ce)!==0)return!0;if(e&de&&(t.f&=~St),(e&Ue)!==0){for(var n=t.deps,r=n.length,i=0;i<r;i++){var s=n[i];if(yn(s)&&Vs(s),s.wv>t.wv)return!0}(e&Ie)!==0&&Ve===null&&te(t,le)}return!1}function Xs(t,e,n=!0){var r=t.reactions;if(r!==null&&!(nt!==null&&nt.has(t)))for(var i=0;i<r.length;i++){var s=r[i];(s.f&de)!==0?Xs(s,e,!1):e===s&&(n?te(s,ce):(s.f&le)!==0&&te(s,Ue),ni(s))}}function Zs(t){var e=xe,n=Se,r=Le,i=F,s=nt,a=be,l=je,u=Ot,c=t.f;xe=null,Se=0,Le=null,F=(c&(Ke|rt))===0?t:null,nt=null,Ht(t.ctx),je=!1,Ot=++At,t.ac!==null&&(Yt(()=>{t.ac.abort(dn)}),t.ac=null);try{t.f|=Pn;var f=t.fn,d=f();t.f|=Mt;var v=os(t);if(Os()&&Le!==null&&!je&&v!==null&&(t.f&(de|Ue|ce))===0)for(var g=0;g<Le.length;g++)Xs(Le[g],t);if(i!==null&&i!==t){if(At++,i.deps!==null)for(let m=0;m<n;m+=1)i.deps[m].rv=At;if(e!==null)for(let m of e)m.rv=At;Le!==null&&(r===null?r=Le:r.push(...Le))}return(t.f&gt)!==0&&(t.f^=gt),d}catch(m){return os(t),hl(m)}finally{t.f^=Pn,xe=e,Se=n,Le=r,F=i,nt=s,Ht(a),je=l,Ot=u}}function os(t){var e=t.deps,n=$?.is_fork;if(xe!==null){var r;if(n||fn(t,Se),e!==null&&Se>0)for(e.length=Se+xe.length,r=0;r<xe.length;r++)e[Se+r]=xe[r];else t.deps=e=xe;if(ri()&&(t.f&Ie)!==0)for(r=Se;r<e.length;r++)(e[r].reactions??=[]).push(t)}else!n&&e!==null&&Se<e.length&&(fn(t,Se),e.length=Se);return e}function xl(t,e){let n=e.reactions;if(n!==null){var r=Na.call(n,t);if(r!==-1){var i=n.length-1;i===0?n=e.reactions=null:(n[r]=n[i],n.pop())}}if(n===null&&(e.f&de)!==0&&(xe===null||!Ln.call(xe,e))){var s=e;(s.f&Ie)!==0&&(s.f^=Ie,s.f&=~St),s.v!==re&&Zr(s),s.ac!==null&&Yt(()=>{s.ac.abort(dn),s.ac=null,te(s,ce)}),wl(s),fn(s,0)}}function fn(t,e){var n=t.deps;if(n!==null)for(var r=e;r<n.length;r++)xl(t,n[r])}function zt(t){var e=t.f;if((e&Te)===0){te(t,le);var n=T,r=Nn;T=t,Nn=(e&(Ke|rt))===0;try{(e&(Be|zr))!==0?Ml(t):si(t),ro(t);var i=Zs(t);t.teardown=typeof i=="function"?i:null,t.wv=Ws;var s}finally{Nn=r,T=n}}}async function xt(){await Promise.resolve(),q()}function o(t){var e=t.f,n=(e&de)!==0;if(F!==null&&!je){var r=T!==null&&(T.f&Te)!==0;if(!r&&(nt===null||!nt.has(t))){var i=F.deps;if((F.f&Pn)!==0)t.rv<At&&(t.rv=At,xe===null&&i!==null&&i[Se]===t?Se++:xe===null?xe=[t]:xe.push(t));else{F.deps??=[],Ln.call(F.deps,t)||F.deps.push(t);var s=t.reactions;s===null?t.reactions=[F]:Ln.call(s,F)||s.push(F)}}}if(ct&&tt.has(t))return tt.get(t);if(n){var a=t;if(ct){var l=a.v;return((a.f&le)===0&&a.reactions!==null||eo(a))&&(l=ei(a)),tt.set(a,l),l}var u=(a.f&Ie)===0&&!je&&F!==null&&(Nn||(F.f&Ie)!==0),c=(a.f&Mt)===0;yn(a)&&(u&&(a.f|=Ie),Vs(a)),u&&!c&&(js(a),Qs(a))}if(Ve?.has(t))return Ve.get(t);if((t.f&gt)!==0)throw t.v;return t.v}function Qs(t){if(t.f|=Ie,t.deps!==null)for(let e of t.deps)(e.reactions??=[]).push(t),(e.f&de)!==0&&(e.f&Ie)===0&&(js(e),Qs(e))}function eo(t){if(t.v===re)return!0;if(t.deps===null)return!1;for(let e of t.deps)if(tt.has(e)||(e.f&de)!==0&&eo(e))return!0;return!1}function wn(t){var e=je;try{return je=!0,t()}finally{je=e}}function Cl(t){T===null&&(F===null&&Ya(),qa()),ct&&za()}function Ol(t,e){var n=e.last;n===null?e.last=e.first=t:(n.next=t,t.prev=n,e.last=t)}function ze(t,e){var n=T;n!==null&&(n.f&De)!==0&&(t|=De);var r={ctx:be,deps:null,nodes:null,f:t|ce|Ie,first:null,fn:e,last:null,next:null,parent:n,b:n&&n.b,prev:null,teardown:null,wv:0,ac:null};$?.register_created_effect(r);var i=r;if((t&Kt)!==0)Pt!==null?Pt.push(r):bt.ensure().schedule(r);else if(e!==null){try{zt(r)}catch(a){throw ve(r),a}i.deps===null&&i.teardown===null&&i.nodes===null&&i.first===i.last&&(i.f&Nt)===0&&(i=i.first,(t&Be)!==0&&(t&mt)!==0&&i!==null&&(i.f|=mt))}if(i!==null&&(i.parent=n,n!==null&&Ol(i,n),F!==null&&(F.f&de)!==0&&(t&rt)===0)){var s=F;(s.effects??=[]).push(i)}return r}function ri(){return F!==null&&!je}function Hn(t){let e=ze(Un,null);return te(e,le),e.teardown=t,e}function ke(t){Cl();var e=T.f,n=!F&&(e&Ke)!==0&&be!==null&&!be.i;if(n){var r=be;(r.e??=[]).push(t)}else return to(t)}function to(t){return ze(Kt|Ba,t)}function Sl(t){bt.ensure();let e=ze(rt|Nt,t);return()=>{ve(e)}}function Tl(t){bt.ensure();let e=ze(rt|Nt,t);return(n={})=>new Promise(r=>{n.outro?hn(e,()=>{ve(e),r(void 0)}):(ve(e),r(void 0))})}function ii(t){return ze(Kt,t)}function $l(t){return ze(Vt|Nt,t)}function zn(t,e=0){return ze(Un|e,t)}function me(t,e=[],n=[],r=[]){Ps(r,e,n,i=>{ze(Un,()=>{t(...i.map(o))})})}function _n(t,e=0){var n=ze(Be|e,t);return n}function no(t,e=0){var n=ze(zr|e,t);return n}function Pe(t){return ze(Ke|Nt,t)}function ro(t){var e=t.teardown;if(e!==null){let n=ct,r=F;is(!0),Re(null);try{e.call(null)}catch(i){Ze(i,t.parent)}finally{is(n),Re(r)}}}function si(t,e=!1){var n=t.first;for(t.first=t.last=null;n!==null;){let i=n.ac;i!==null&&Yt(()=>{i.abort(dn)});var r=n.next;(n.f&rt)!==0?n.parent=null:ve(n,e),n=r}}function Ml(t){for(var e=t.first;e!==null;){var n=e.next;(e.f&Ke)===0&&ve(e),e=n}}function ve(t,e=!0){var n=!1;(e||(t.f&Pa)!==0)&&t.nodes!==null&&t.nodes.end!==null&&(io(t.nodes.start,t.nodes.end),n=!0),t.f|=Dn,si(t,e&&!n),fn(t,0);var r=t.nodes&&t.nodes.t;if(r!==null)for(let s of r)s.stop();ro(t),t.f^=Dn,t.f|=Te;var i=t.parent;i!==null&&i.first!==null&&so(t),t.next=t.prev=t.teardown=t.ctx=t.deps=t.fn=t.nodes=t.ac=t.b=null}function io(t,e){for(;t!==null;){var n=t===e?null:st(t);t.remove(),t=n}}function so(t){var e=t.parent,n=t.prev,r=t.next;n!==null&&(n.next=r),r!==null&&(r.prev=n),e!==null&&(e.first===t&&(e.first=r),e.last===t&&(e.last=n))}function hn(t,e,n=!0){var r=[];t.f|=qr,oo(t,r,!0);var i=()=>{n&&ve(t),e&&e()},s=r.length;if(s>0){var a=()=>--s||i();for(var l of r)l.out(a)}else i()}function oo(t,e,n){if((t.f&De)===0){t.f^=De;var r=t.nodes&&t.nodes.t;if(r!==null)for(let l of r)(l.is_global||n)&&e.push(l);for(var i=t.first;i!==null;){var s=i.next;if((i.f&rt)===0){var a=(i.f&mt)!==0||(i.f&Ke)!==0&&(t.f&Be)!==0;oo(i,e,a?n:!1)}i=s}}}function as(t){t.f&=~qr,ao(t,!0)}function ao(t,e){if((t.f&qr)===0&&(t.f&De)!==0){t.f^=De,(t.f&le)===0&&(te(t,ce),bt.ensure().schedule(t));for(var n=t.first;n!==null;){var r=n.next,i=(n.f&mt)!==0||(n.f&Ke)!==0;ao(n,i?e:!1),n=r}var s=t.nodes&&t.nodes.t;if(s!==null)for(let a of s)(a.is_global||e)&&a.in()}}function lo(t,e){if(t.nodes)for(var n=t.nodes.start,r=t.nodes.end;n!==null;){var i=n===r?null:st(n);e.append(n),n=i}}function Nl(t){let e=0,n=bn(0),r;return()=>{ri()&&(o(n),zn(()=>(e===0&&(r=wn(()=>t(()=>un(n)))),e+=1,()=>{Qe(()=>{e-=1,e===0&&(r?.(),r=void 0,un(n))})})))}}function ls(t){let e={get:n=>sn(e.store)[n],set:(n,r)=>{typeof n=="string"?Object.assign(sn(e.store),{[n]:r}):Object.assign(sn(e.store),n),e.store.set(sn(e.store))},store:dl(t)};return e}globalThis.$altcha=globalThis.$altcha||{algorithms:new Map,defaults:ls({}),i18n:ls({}),instances:new Set,plugins:new Set};var Fl={ariaLinkLabel:"Altcha (official website)",cancel:"Cancel",enterCode:"Enter code",enterCodeAria:"Enter code you hear. Press Space to play audio.",enterCodeFromImage:"To proceed, please enter the code from the image below.",error:"Verification failed. Try again later.",expired:"Verification expired. Try again.",footer:'Protected by <a href="https://altcha.org/" target="_blank" rel="noopener" aria-label="Altcha (official website, opens in new tab)">ALTCHA</a>',getAudioChallenge:"Get an audio challenge",label:"I'm not a robot",loading:"Loading...",reload:"Reload",verify:"Verify",verificationRequired:"Verification required!",verified:"Verified",verifying:"Verifying...",waitAlert:"Verifying... please wait."};globalThis.$altcha.i18n.set("en",Fl);var Ll="5";typeof window<"u"&&((window.__svelte??={}).v??=new Set).add(Ll);var an=Symbol("events"),co=new Set,Lr=new Set;function uo(t,e,n,r={}){function i(s){if(r.capture||Ir.call(e,s),!s.cancelBubble)return Yt(()=>n?.call(this,s))}return t.startsWith("pointer")||t.startsWith("touch")||t==="wheel"?Qe(()=>{e.addEventListener(t,i,r)}):e.addEventListener(t,i,r),i}function ae(t,e,n,r,i){var s={capture:r,passive:i},a=uo(t,e,n,s);(e===document.body||e===window||e===document||e instanceof HTMLMediaElement)&&Hn(()=>{e.removeEventListener(t,a,s)})}function qn(t,e,n){(e[an]??={})[t]=n}function Yn(t){for(var e=0;e<t.length;e++)co.add(t[e]);for(var n of Lr)n(t)}var br=null,yr=!1;function Ir(t){var e=this,n=e.ownerDocument,r=t.type,i=t.composedPath?.()||[],s=i[0]||t.target;br=t,yr||(yr=!0,setTimeout(()=>{yr=!1,br=null}));var a=0,l=br===t&&t[an];if(l){var u=i.indexOf(l);if(u!==-1&&(e===document||e===window)){t[an]=e;return}var c=i.indexOf(e);if(c===-1)return;u<=c&&(a=u)}if(s=i[a]||t.target,s!==e){Ut(t,"currentTarget",{configurable:!0,get(){return s||n}});var f=F,d=T;Re(null),it(null);try{for(var v,g=[];s!==null&&s!==e;){try{var m=s[an]?.[r];m!=null&&(!s.disabled||t.target===s)&&m.call(s,t)}catch(k){v?g.push(k):v=k}if(t.cancelBubble)break;a++,s=a<i.length?i[a]:null}if(v){for(let k of g)queueMicrotask(()=>{throw k});throw v}}finally{t[an]=e,delete t.currentTarget,Re(f),it(d)}}}var Il=globalThis?.window?.trustedTypes&&globalThis.window.trustedTypes.createPolicy("svelte-trusted-html",{createHTML:t=>t});function Dl(t){return Il?.createHTML(t)??t}function ho(t){var e=Xr("template");return e.innerHTML=Dl(t.replaceAll("<!>","<!---->")),e.content}function $e(t,e){var n=T;n.nodes===null&&(n.nodes={start:t,end:e,a:null,t:null})}function ee(t,e){var n=(e&el)!==0,r=(e&tl)!==0,i,s=!t.startsWith("<!>");return()=>{if(S)return $e(D,null),D;i===void 0&&(i=ho(s?t:"<!>"+t),n||(i=Ae(i)));var a=r||Ms?document.importNode(i,!0):i.cloneNode(!0);if(n){var l=Ae(a),u=a.lastChild;$e(l,u)}else $e(a,a);return a}}function Rl(t,e,n="svg"){var r=!t.startsWith("<!>"),i=`<${n}>${r?t:"<!>"+t}</${n}>`,s;return()=>{if(S)return $e(D,null),D;if(!s){var a=ho(i),l=Ae(a);s=Ae(l)}var u=s.cloneNode(!0);return $e(u,u),u}}function oi(t,e){return Rl(t,e,"svg")}function Tn(t=""){if(!S){var e=He(t+"");return $e(e,e),e}var n=D;return n.nodeType!==vn?(n.before(n=He()),_e(n)):Kn(n),$e(n,n),n}function cs(){if(S)return $e(D,null),D;var t=document.createDocumentFragment(),e=document.createComment(""),n=He();return t.append(e,n),$e(e,n),t}function I(t,e){if(S){var n=T;((n.f&Mt)===0||n.nodes.end===null)&&(n.nodes.end=D),$t();return}t!==null&&t.before(e)}function Pl(t){return t.endsWith("capture")&&t!=="gotpointercapture"&&t!=="lostpointercapture"}var Bl=["beforeinput","click","change","dblclick","contextmenu","focusin","focusout","input","keydown","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","pointerdown","pointermove","pointerout","pointerover","pointerup","touchend","touchmove","touchstart"];function Vl(t){return Bl.includes(t)}var jl={formnovalidate:"formNoValidate",ismap:"isMap",nomodule:"noModule",playsinline:"playsInline",readonly:"readOnly",defaultvalue:"defaultValue",defaultchecked:"defaultChecked",srcobject:"srcObject",novalidate:"noValidate",allowfullscreen:"allowFullscreen",disablepictureinpicture:"disablePictureInPicture",disableremoteplayback:"disableRemotePlayback"};function Ul(t){return t=t.toLowerCase(),jl[t]??t}var Kl=["touchstart","touchmove"];function Hl(t){return Kl.includes(t)}var zl=mt|Nt;function ql(t,e,n,r){new Dr(t,e,n,r)}var Dr=class{parent;is_pending=!1;transform_error;#e;#t=S?D:null;#n;#l;#i;#o=null;#r=null;#a=null;#s=null;#v=0;#c=0;#u=!1;#f=new Set;#g=new Set;#h=null;#y=Nl(()=>(this.#h=bn(this.#v),()=>{this.#h=null}));constructor(e,n,r,i){this.#e=e,this.#n=n,this.#l=s=>{var a=T;a.b=this,a.f|=xr,r(s)},this.parent=T.b,this.transform_error=i??this.parent?.transform_error??(s=>s),this.#i=_n(()=>{if(S){let s=this.#t;$t();let a=s.data===As;if(s.data.startsWith(Zi)){let u=JSON.parse(s.data.slice(Zi.length));this.#w(u)}else a?this.#E():this.#m()}else this.#d()},zl),S&&(this.#e=D)}#m(){try{this.#o=Pe(()=>this.#l(this.#e))}catch(e){this.error(e)}}#w(e){let n=this.#n.failed,{reset:r,invoke_onerror:i}=this.#_(e);Qe(i),n&&(this.#a=Pe(()=>{n(this.#e,()=>e,()=>r)}))}#_(e){var n=!1,r=!1;let i=()=>{if(n){ll();return}n=!0,r&&Qa(),this.#a!==null&&hn(this.#a,()=>{this.#a=null}),this.#p(()=>{this.#d()})};return{reset:i,invoke_onerror:()=>{try{r=!0,this.#n.onerror?.(e,i),r=!1}catch(a){Ze(a,this.#i&&this.#i.parent)}}}}#E(){let e=this.#n.pending;e&&(this.is_pending=!0,this.#r=Pe(()=>e(this.#e)),Qe(()=>{var n=this.#s=document.createDocumentFragment(),r=He(),i=!1;if(n.append(r),this.#o=this.#p(()=>{try{return Pe(()=>this.#l(r))}catch(s){try{this.error(s),i=!0}catch(a){Ze(a,this.#i.parent)}return null}}),this.#o===null){this.#s=null,i&&this.#b($);return}this.#c===0&&(this.#e.before(n),this.#s=null,hn(this.#r,()=>{this.#r=null}),this.#b($))}))}#d(){try{if(this.is_pending=this.has_pending_snippet(),this.#c=0,this.#v=0,this.#o=Pe(()=>{this.#l(this.#e)}),this.#c>0){var e=this.#s=document.createDocumentFragment();lo(this.#o,e);let n=this.#n.pending;this.#r=Pe(()=>n(this.#e))}else this.#b($)}catch(n){this.error(n)}}#b(e){this.is_pending=!1,e.transfer_effects(this.#f,this.#g)}defer_effect(e){Is(e,this.#f,this.#g)}is_rendered(){return!this.is_pending&&(!this.parent||this.parent.is_rendered())}has_pending_snippet(){return!!this.#n.pending}#p(e){var n=T,r=F,i=be;it(this.#i),Re(this.#i),Ht(this.#i.ctx);try{return bt.ensure(),e()}finally{it(n),Re(r),Ht(i)}}#k(e,n){if(!this.has_pending_snippet()){this.parent&&this.parent.#k(e,n);return}this.#c+=e,this.#c===0&&(this.#b(n),this.#r&&hn(this.#r,()=>{this.#r=null}),this.#s&&(this.#e.before(this.#s),this.#s=null))}update_pending_count(e,n){this.#k(e,n),this.#v+=e,!(!this.#h||this.#u)&&(this.#u=!0,Qe(()=>{this.#u=!1,this.#h&&jn(this.#h,this.#v)}))}get_effect_pending(){return this.#y(),o(this.#h)}error(e){if(!this.#n.onerror&&!this.#n.failed)throw e;$?.is_fork?(this.#o&&$.skip_effect(this.#o),this.#r&&$.skip_effect(this.#r),this.#a&&$.skip_effect(this.#a),$.oncommit(()=>{this.#A(e)})):this.#A(e)}#A(e){this.#o&&(ve(this.#o),this.#o=null),this.#r&&(ve(this.#r),this.#r=null),this.#a&&(ve(this.#a),this.#a=null),S&&(_e(this.#t),Wr(),_e(Jr()));let n=this.#n.failed,r=i=>{let{reset:s,invoke_onerror:a}=this.#_(i);a(),n&&(this.#a=this.#p(()=>{try{return Pe(()=>{var l=T;l.b=this,l.f|=xr,n(this.#e,()=>i,()=>s)})}catch(l){return Ze(l,this.#i.parent),null}}))};Qe(()=>{var i;try{i=this.transform_error(e)}catch(s){Ze(s,this.#i&&this.#i.parent);return}i!==null&&typeof i=="object"&&typeof i.then=="function"?i.then(r,s=>Ze(s,this.#i&&this.#i.parent)):r(i)})}};function Xe(t,e){var n=e==null?"":typeof e=="object"?`${e}`:e;n!==(t[Sr]??=t.nodeValue)&&(t[Sr]=n,t.nodeValue=`${n}`)}function fo(t,e){return po(t,e)}function Yl(t,e){$r(),e.intro=e.intro??!1;let n=e.target,r=S,i=D;try{for(var s=Ae(n);s&&(s.nodeType!==gn||s.data!==Yr);)s=st(s);if(!s)throw Tt;et(!0),_e(s);let a=po(t,{...e,anchor:s});return et(!1),a}catch(a){if(a instanceof Error&&a.message.split(`
`).some(l=>l.startsWith("https://svelte.dev/e/")))throw a;return a!==Tt&&console.warn("Failed to hydrate: ",a),e.recover===!1&&Wa(),$r(),cl(n),et(!1),fo(t,e)}finally{et(r),_e(i)}}var $n=new Map;function po(t,{target:e,anchor:n,props:r={},events:i,context:s,intro:a=!0,transformError:l}){$r();var u=void 0,c=Tl(()=>{var f=n??e.appendChild(He());ql(f,{pending:()=>{}},g=>{ut({});var m=be;if(s&&(m.c=s),i&&(r.$$events=i),S&&$e(g,null),u=t(g,r)||Gr(),S&&(T.nodes.end=D,D===null||D.nodeType!==gn||D.data!==xs))throw mn(),Tt;ht()},l);var d=new Set,v=g=>{for(var m=0;m<g.length;m++){var k=g[m];if(!d.has(k)){d.add(k);var N=Hl(k);for(let ne of[e,document]){var H=$n.get(ne);H===void 0&&(H=new Map,$n.set(ne,H));var V=H.get(k);V===void 0?(ne.addEventListener(k,Ir,{passive:N}),H.set(k,1)):H.set(k,V+1)}}}};return v(Fa(co)),Lr.add(v),()=>{for(var g of d)for(let N of[e,document]){var m=$n.get(N),k=m.get(g);--k==0?(N.removeEventListener(g,Ir),m.delete(g),m.size===0&&$n.delete(N)):m.set(g,k)}Lr.delete(v),f!==n&&f.parentNode?.removeChild(f)}});return Rr.set(u,c),u}var Rr=new WeakMap;function Gl(t,e){let n=Rr.get(t);return n?(Rr.delete(t),n(e)):Promise.resolve()}var qt=class{anchor;#e=new Map;#t=new Map;#n=new Map;#l=new Set;#i=!0;constructor(e,n=!0){this.anchor=e,this.#i=n}#o=e=>{if(this.#e.has(e)){var n=this.#e.get(e),r=this.#t.get(n);if(r)as(r),this.#l.delete(n);else{var i=this.#n.get(n);i&&(as(i.effect),this.#t.set(n,i.effect),this.#n.delete(n),i.fragment.lastChild.remove(),this.anchor.before(i.fragment),r=i.effect)}for(let[s,a]of this.#e){if(this.#e.delete(s),s===e)break;let l=this.#n.get(a);l&&(ve(l.effect),this.#n.delete(a))}for(let[s,a]of this.#t){if(s===n||this.#l.has(s))continue;let l=()=>{if(Array.from(this.#e.values()).includes(s)){var c=document.createDocumentFragment();lo(a,c),c.append(He()),this.#n.set(s,{effect:a,fragment:c})}else ve(a);this.#l.delete(s),this.#t.delete(s)};this.#i||!r?(this.#l.add(s),hn(a,l,!1)):l()}}};#r=e=>{this.#e.delete(e);let n=Array.from(this.#e.values());for(let[r,i]of this.#n)n.includes(r)||(ve(i.effect),this.#n.delete(r))};ensure(e,n){var r=$,i=ul();if(n&&!this.#t.has(e)&&!this.#n.has(e))if(i){var s=document.createDocumentFragment(),a=He();s.append(a),this.#n.set(e,{effect:Pe(()=>n(a)),fragment:s})}else this.#t.set(e,Pe(()=>n(this.anchor)));if(this.#e.set(r,e),i){for(let[l,u]of this.#t)l===e?r.unskip_effect(u):r.skip_effect(u);for(let[l,u]of this.#n)l===e?r.unskip_effect(u.effect):r.skip_effect(u.effect);r.oncommit(this.#o),r.ondiscard(this.#r)}else S&&(this.anchor=D),this.#o(r)}};function Wl(t,e,...n){var r=new qt(t);_n(()=>{let i=e()??null;r.ensure(i,i&&(s=>i(s,...n)))},mt)}function ai(t){be===null&&Ka(),ke(()=>{let e=wn(t);if(typeof e=="function")return e})}function oe(t,e,n=!1){var r;S&&(r=D,$t());var i=new qt(t),s=n?mt:0;function a(l,u){if(S){var c=Ts(r);if(l!==parseInt(c.substring(1))){var f=Jr();_e(f),i.anchor=f,et(!1),i.ensure(l,u),et(!0);return}}i.ensure(l,u)}_n(()=>{var l=!1;e((u,c=0)=>{l=!0,a(c,u)}),l||a(-1,null)},s)}var Jl=Symbol("NaN");function Xl(t,e,n){S&&$t();var r=new qt(t);_n(()=>{var i=e();i!==i&&(i=Jl),r.ensure(i,n)})}function vo(t,e,n=!1,r=!1,i=!1,s=!1){var a=t,l="";if(n){var u=t;S&&(a=_e(Ae(u)))}me(()=>{var c=T;if(l===(l=e()??"")){S&&$t();return}if(n&&!S){c.nodes=null,u.innerHTML=l,l!==""&&$e(Ae(u),u.lastChild);return}if(c.nodes!==null&&(io(c.nodes.start,c.nodes.end),c.nodes=null),l!==""){if(S){D.data;for(var f=$t(),d=f;f!==null&&(f.nodeType!==gn||f.data!=="");)d=f,f=st(f);if(f===null)throw mn(),Tt;$e(D,d),a=_e(f);return}var v=r?nl:i?rl:void 0,g=Xr(r?"svg":i?"math":"template",v);g.innerHTML=l;var m=r||i?g:g.content;if($e(Ae(m),m.lastChild),r||i)for(;Ae(m);)a.before(Ae(m));else a.before(m)}})}function Zl(t,e,n){var r;S&&(r=D,$t());var i=new qt(t);_n(()=>{var s=e()??null;if(S){var a=Ts(r),l=a===Yr,u=s!==null;if(l!==u){var c=Jr();_e(c),i.anchor=c,et(!1),i.ensure(s,s&&(f=>n(f,s))),et(!0);return}}i.ensure(s,s&&(f=>n(f,s)))},mt)}function Ql(t,e){var n=void 0,r;no(()=>{n!==(n=e())&&(r&&(ve(r),r=null),n&&(r=Pe(()=>{ii(()=>n(t))})))})}function go(t){var e,n,r="";if(typeof t=="string"||typeof t=="number")r+=t;else if(typeof t=="object")if(Array.isArray(t)){var i=t.length;for(e=0;e<i;e++)t[e]&&(n=go(t[e]))&&(r&&(r+=" "),r+=n)}else for(n in t)t[n]&&(r&&(r+=" "),r+=n);return r}function ec(){for(var t,e,n=0,r="",i=arguments.length;n<i;n++)(t=arguments[n])&&(e=go(t))&&(r&&(r+=" "),r+=e);return r}function tc(t){return typeof t=="object"?ec(t):t??""}var us=[...` 	
\r\f\xA0\v\uFEFF`];function nc(t,e,n){var r=t==null?"":""+t;if(n){for(var i of Object.keys(n))if(n[i])r=r?r+" "+i:i;else if(r.length)for(var s=i.length,a=0;(a=r.indexOf(i,a))>=0;){var l=a+s;(a===0||us.includes(r[a-1]))&&(l===r.length||us.includes(r[l]))?r=(a===0?"":r.substring(0,a))+r.substring(l+1):a=l}}return r===""?null:r}function hs(t,e=!1){var n=e?" !important;":";",r="";for(var i of Object.keys(t)){var s=t[i];s!=null&&s!==""&&(r+=" "+i+": "+s+n)}return r}function wr(t){return t[0]!=="-"||t[1]!=="-"?t.toLowerCase():t}function rc(t,e){if(e){var n="",r,i;if(Array.isArray(e)?(r=e[0],i=e[1]):r=e,t){t=String(t).replaceAll(/\/\*.*?\*\//g,"").trim();var s=!1,a=0,l=!1,u=[];r&&u.push(...Object.keys(r).map(wr)),i&&u.push(...Object.keys(i).map(wr));var c=0,f=-1;let k=t.length;for(var d=0;d<k;d++){var v=t[d];if(l?v==="/"&&t[d-1]==="*"&&(l=!1):s?s===v&&(s=!1):v==="/"&&t[d+1]==="*"?l=!0:v==='"'||v==="'"?s=v:v==="("?a++:v===")"&&a--,!l&&s===!1&&a===0){if(v===":"&&f===-1)f=d;else if(v===";"||d===k-1){if(f!==-1){var g=wr(t.substring(c,f).trim());if(!u.includes(g)){v!==";"&&d++;var m=t.substring(c,d).trim();n+=" "+m+";"}}c=d+1,f=-1}}}}return r&&(n+=hs(r)),i&&(n+=hs(i,!0)),n=n.trim(),n===""?null:n}return t==null?null:String(t)}function ic(t,e,n,r,i,s){var a=t[Cr];if(S||a!==n||a===void 0){var l=nc(n,r,s);(!S||l!==t.getAttribute("class"))&&(l==null?t.removeAttribute("class"):e?t.className=l:t.setAttribute("class",l)),t[Cr]=n}else if(s&&i!==s)for(var u in s){var c=!!s[u];(i==null||c!==!!i[u])&&t.classList.toggle(u,c)}return s}function _r(t,e={},n,r){for(var i in n){var s=n[i];e[i]!==s&&(n[i]==null?t.style.removeProperty(i):t.style.setProperty(i,s,r))}}function sc(t,e,n,r){var i=t[Or];if(S||i!==e){var s=rc(e,r);(!S||s!==t.getAttribute("style"))&&(s==null?t.removeAttribute("style"):t.style.cssText=s),t[Or]=e}else r&&(Array.isArray(r)?(_r(t,n?.[0],r[0]),_r(t,n?.[1],r[1],"important")):_r(t,n,r));return r}function mo(t,e){e?t.hasAttribute("selected")||t.setAttribute("selected",""):t.removeAttribute("selected")}function fs(t,e){var n=!("__defaultValue"in t);!n&&t.__defaultValue===e||(t.__defaultValue=e,bo(t,!n||"__value"in t))}function bo(t,e){var n=t.__defaultValue,r=t.multiple,i=r?n??[]:null;if(!(r&&!Hr(i))){var s=t.selectedIndex,a=e&&r?new Set(t.selectedOptions):null;for(var l of t.options){var u=Br(l);mo(l,r?i.includes(u):$s(u,n))}if(e)if(a!==null)for(l of t.options){var c=a.has(l);l.selected!==c&&(l.selected=c)}else t.selectedIndex!==s&&(t.selectedIndex=s)}}function Pr(t,e,n=!1){if(t.multiple){if(e==null)return;if(!Hr(e))return al();for(var r of t.options)r.selected=e.includes(Br(r));return}for(r of t.options){var i=Br(r);if($s(i,e)){r.selected=!0;return}}(!n||e!==void 0)&&(t.selectedIndex=-1)}function oc(t){var e=new MutationObserver(n=>{n.every(ac)||("__defaultValue"in t&&bo(t,!1),"__value"in t&&Pr(t,t.__value))});e.observe(t,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["value"]}),Hn(()=>{e.disconnect()})}function Br(t){return"__value"in t?t.__value:t.value}function ac(t){if(t.target.closest("selectedcontent")!==null)return!0;if(t.type==="childList"){var e=[...t.addedNodes,...t.removedNodes];return e.length>0&&e.every(n=>n.nodeName==="SELECTEDCONTENT")}return!1}var nn=Symbol("class"),rn=Symbol("style"),yo=Symbol("is custom element"),wo=Symbol("is html"),lc=pn?"link":"LINK",ds=pn?"input":"INPUT",cc=pn?"option":"OPTION",_o=pn?"select":"SELECT",uc=pn?"progress":"PROGRESS";function li(t){if(S){var e=!1,n=()=>{if(!e){if(e=!0,t.hasAttribute("value")){var r=t.value;P(t,"value",null),t.value=r}if(t.hasAttribute("checked")){var i=t.checked;P(t,"checked",null),t.checked=i}}};t[ln]=n,Qe(n),Rs()}}function hc(t,e){var n=ci(t);n.value===(n.value=e??void 0)||t.value===e&&(e!==0||t.nodeName!==uc)||(t.value=e??"")}function P(t,e,n,r){var i=ci(t);S&&(i[e]=t.getAttribute(e),e==="src"||e==="srcset"||e==="href"&&t.nodeName===lc)||i[e]!==(i[e]=n)&&(e==="loading"&&(t[ja]=n),n==null?t.removeAttribute(e):typeof n!="string"&&Eo(t).has(e)?t[e]=n:t.setAttribute(e,n))}function fc(t,e,n,r,i=!1,s=!1){S&&i&&t.nodeName===ds&&("defaultValue"in n||"defaultChecked"in n||li(t));var a=ci(t),l=a[yo],u=!a[wo];let c=S&&l;c&&et(!1);var f=e||{},d=t.nodeName===cc,v=t.nodeName===_o;for(var g in e)!(g in n)&&g[0]+g[1]!=="$$"&&(n[g]=null);n.class?n.class=tc(n.class):n[nn]&&(n.class=null),n[rn]&&(n.style??=null);var m=Eo(t);if(t.nodeName===ds&&"type"in n&&("value"in n||"__value"in n)){var k=n.type;(k!==f.type||k===void 0&&t.hasAttribute("type"))&&(f.type=k,P(t,"type",k))}for(let E in n){let L=n[E];if(d&&E==="value"&&L==null){t.value=t.__value="",f[E]=L;continue}if(E==="class"){var N=t.namespaceURI==="http://www.w3.org/1999/xhtml";ic(t,N,L,r,e?.[nn],n[nn]),f[E]=L,f[nn]=n[nn];continue}if(E==="style"){sc(t,L,e?.[rn],n[rn]),f[E]=L,f[rn]=n[rn];continue}var H=f[E];if(!(L===H&&!(L===void 0&&t.hasAttribute(E)))){f[E]=L;var V=E[0]+E[1];if(V!=="$$")if(V==="on"){let Z={},j="$$"+E,R=E.slice(2);var ne=Vl(R);if(Pl(R)&&(R=R.slice(0,-7),Z.capture=!0),!ne&&H){if(L!=null)continue;t.removeEventListener(R,f[j],Z),f[j]=null}if(ne)qn(R,t,L),Yn([R]);else if(L!=null){let Ce=function(ge){f[E].call(this,ge)};var se=Ce;f[j]=uo(R,t,Ce,Z)}}else if(E==="style")P(t,E,L);else if(E==="autofocus")vl(t,!!L);else if(!l&&(E==="__value"||E==="value"&&L!=null))t.value=t.__value=L;else if(E==="selected"&&d)mo(t,L);else{var K=E;u||(K=Ul(K));var Ye=K==="defaultValue"||K==="defaultChecked";if(v&&K==="defaultValue")continue;if(L==null&&!l&&!Ye)if(a[E]=null,K==="value"||K==="checked"){let Z=t,j=e===void 0;if(K==="value"){let R=Z.defaultValue;Z.removeAttribute(K),Z.defaultValue=R,Z.value=Z.__value=j?R:null}else{let R=Z.defaultChecked;Z.removeAttribute(K),Z.defaultChecked=R,Z.checked=j?R:!1}}else t.removeAttribute(E);else Ye||(l||typeof L!="string")&&m.has(K)?(t[K]=L,K in a&&(a[K]=re)):typeof L!="function"&&P(t,K,L)}}}return c&&et(!0),f}function Gn(t,e,n=[],r=[],i=[],s,a=!1,l=!1){Ps(i,n,r,u=>{var c=void 0,f={},d=t.nodeName===_o,v=!1;if(no(()=>{var m=e(...u.map(o)),k=fc(t,c,m,s,a,l);if(v&&d){var N=t;"defaultValue"in m&&fs(N,m.defaultValue),"value"in m&&Pr(N,m.value)}for(let V of Object.getOwnPropertySymbols(f))m[V]||ve(f[V]);for(let V of Object.getOwnPropertySymbols(m)){var H=m[V];V.description===il&&(!c||H!==c[V])&&(f[V]&&ve(f[V]),f[V]=Pe(()=>Ql(t,()=>H))),k[V]=H}c=k}),d){var g=t;ii(()=>{var m=c;"defaultValue"in m&&fs(g,m.defaultValue),Pr(g,m.value,!0),oc(g)})}v=!0})}function ci(t){return t[_s]??={[yo]:t.nodeName.includes("-"),[wo]:t.namespaceURI===Cs}}var ps=new Map;function Eo(t){var e=t.getAttribute("is")||t.nodeName,n=ps.get(e);if(n)return n;ps.set(e,n=new Set);for(var r,i=t,s=Element.prototype;s!==i;){r=La(i);for(var a in r)r[a].set&&a!=="innerHTML"&&a!=="textContent"&&a!=="innerText"&&n.add(a);i=bs(i)}return n}function dc(t,e,n=e){var r=new WeakSet;gl(t,"input",async i=>{var s=i?t.defaultValue:t.value;if(s=Er(t)?kr(s):s,n(s),$!==null&&r.add($),await xt(),s!==(s=e())){var a=t.selectionStart,l=t.selectionEnd,u=t.value.length;if(t.value=s??"",l!==null){var c=t.value.length;a===l&&l===u&&c>u?(t.selectionStart=c,t.selectionEnd=c):(t.selectionStart=a,t.selectionEnd=Math.min(l,c))}}}),(S&&t.defaultValue!==t.value||wn(e)==null&&t.value)&&(n(Er(t)?kr(t.value):t.value),$!==null&&r.add($)),zn(()=>{var i=e();if(t===document.activeElement){var s=$;if(r.has(s))return}Er(t)&&i===kr(t.value)||t.type==="date"&&!i&&!t.value||i!==t.value&&(t.value=i??"")})}function Er(t){var e=t.type;return e==="number"||e==="range"}function kr(t){return t===""?null:+t}function Ar(t,e){return t===e||t?.[jt]===e}function yt(t=Gr(),e,n,r){var i=be.r,s=T;return ii(()=>{var a,l;return zn(()=>{a=l,l=[],wn(()=>{Ar(n(...l),t)||(e(t,...l),a&&Ar(n(...a),t)&&e(null,...a))})}),()=>{let u=s;for(;u!==i&&u.parent!==null&&u.parent.f&Dn;)u=u.parent;let c=()=>{l&&Ar(n(...l),t)&&e(null,...l)},f=u.teardown;u.teardown=()=>{c(),f?.()}}}),t}var pc={get(t,e){if(!t.exclude.has(e))return t.props[e]},set(t,e){return!1},getOwnPropertyDescriptor(t,e){if(!t.exclude.has(e)&&e in t.props)return{enumerable:!0,configurable:!0,value:t.props[e]}},has(t,e){return t.exclude.has(e)?!1:e in t.props},ownKeys(t){return Reflect.ownKeys(t.props).filter(e=>!t.exclude.has(e))}};function Wn(t,e,n){return new Proxy({props:t,exclude:e},pc)}function X(t,e,n,r){var i=r,s=!0,a=()=>(s&&(s=!1,i=r),i),l;l=t[e],l===void 0&&r!==void 0&&(l=a());var u;u=()=>{var v=t[e];return v===void 0?a():(s=!0,v)};var c=!1,f=Qr(()=>(c=!1,u())),d=T;return(function(v,g){if(arguments.length>0){let m=g?o(f):v;return w(f,m),c=!0,i!==void 0&&(i=m),v}return ct&&c||(d.f&Te)!==0?f.v:o(f)})}function vc(t){return new Vr(t)}var Vr=class{#e;#t;constructor(e){var n=new Map,r=(s,a)=>{var l=qs(a,!1,!1);return n.set(s,l),l};let i=new Proxy({...e.props||{},$$events:{}},{get(s,a){return o(n.get(a)??r(a,Reflect.get(s,a)))},has(s,a){return a===Va?!0:(o(n.get(a)??r(a,Reflect.get(s,a))),Reflect.has(s,a))},set(s,a,l){return w(n.get(a)??r(a,l),l),Reflect.set(s,a,l)}});this.#t=(e.hydrate?Yl:fo)(e.component,{target:e.target,anchor:e.anchor,props:i,context:e.context,intro:e.intro??!1,recover:e.recover,transformError:e.transformError}),(!e?.props?.$$host||e.sync===!1)&&q(),this.#e=i.$$events;for(let s of Object.keys(this.#t))s==="$set"||s==="$destroy"||s==="$on"||Ut(this,s,{get(){return this.#t[s]},set(a){this.#t[s]=a},enumerable:!0});this.#t.$set=s=>{Object.assign(i,s)},this.#t.$destroy=()=>{Gl(this.#t)}}$set(e){this.#t.$set(e)}$on(e,n){this.#e[e]=this.#e[e]||[];let r=(...i)=>n.call(this,...i);return this.#e[e].push(r),()=>{this.#e[e]=this.#e[e].filter(i=>i!==r)}}$destroy(){this.#t.$destroy()}},ko=class{};typeof HTMLElement=="function"&&(ko=class extends HTMLElement{$$ctor;$$s;$$c;$$cn=!1;$$d={};$$r=!1;$$p_d={};$$l={};$$l_u=new Map;$$me;$$shadowRoot=null;constructor(t,e,n){super(),this.$$ctor=t,this.$$s=e,n&&(this.$$shadowRoot=this.attachShadow(n))}addEventListener(t,e,n){if(this.$$l[t]=this.$$l[t]||[],this.$$l[t].push(e),this.$$c){let r=this.$$c.$on(t,e);this.$$l_u.set(e,r)}super.addEventListener(t,e,n)}removeEventListener(t,e,n){if(super.removeEventListener(t,e,n),this.$$c){let r=this.$$l_u.get(e);r&&(r(),this.$$l_u.delete(e))}}async connectedCallback(){if(this.$$cn=!0,!this.$$c){let e=function(i){return s=>{let a=Xr("slot");i!=="default"&&(a.name=i),I(s,a)}};var t=e;if(await Promise.resolve(),!this.$$cn||this.$$c)return;let n={},r=gc(this);for(let i of this.$$s)i in r&&(i==="default"&&!this.$$d.children?(this.$$d.children=e(i),n.default=!0):n[i]=e(i));for(let i of this.attributes){let s=this.$$g_p(i.name);s in this.$$d||(this.$$d[s]=Fn(s,i.value,this.$$p_d,"toProp"))}for(let i in this.$$p_d)!(i in this.$$d)&&this[i]!==void 0&&(this.$$d[i]=this[i],delete this[i]);this.$$c=vc({component:this.$$ctor,target:this.$$shadowRoot||this,props:{...this.$$d,$$slots:n,$$host:this}}),this.$$me=Sl(()=>{zn(()=>{this.$$r=!0;for(let i of In(this.$$c)){if(!this.$$p_d[i]?.reflect)continue;this.$$d[i]=this.$$c[i];let s=Fn(i,this.$$d[i],this.$$p_d,"toAttribute");s==null?this.removeAttribute(this.$$p_d[i].attribute||i):this.setAttribute(this.$$p_d[i].attribute||i,s)}this.$$r=!1})});for(let i in this.$$l)for(let s of this.$$l[i]){let a=this.$$c.$on(i,s);this.$$l_u.set(s,a)}this.$$l={}}}attributeChangedCallback(t,e,n){this.$$r||(t=this.$$g_p(t),this.$$d[t]=Fn(t,n,this.$$p_d,"toProp"),this.$$c?.$set({[t]:this.$$d[t]}))}disconnectedCallback(){this.$$cn=!1,Promise.resolve().then(()=>{!this.$$cn&&this.$$c&&(this.$$c.$destroy(),this.$$me(),this.$$c=void 0)})}$$g_p(t){return In(this.$$p_d).find(e=>this.$$p_d[e].attribute===t||!this.$$p_d[e].attribute&&e.toLowerCase()===t)||t}});function Fn(t,e,n,r){let i=n[t]?.type;if(e=i==="Boolean"&&typeof e!="boolean"?e!=null:e,!r||!n[t])return e;if(r==="toAttribute")switch(i){case"Object":case"Array":return e==null?null:JSON.stringify(e);case"Boolean":return e?"":null;case"Number":return e??null;default:return e}else switch(i){case"Object":case"Array":return e&&JSON.parse(e);case"Boolean":return e;case"Number":return e!=null?+e:e;default:return e}}function gc(t){let e={};return t.childNodes.forEach(n=>{e[n.slot||"default"]=!0}),e}function wt(t,e,n,r,i,s){let a=class extends ko{constructor(){super(t,n,i),this.$$p_d=e}static get observedAttributes(){return In(e).map(l=>(e[l].attribute||l).toLowerCase())}};return In(e).forEach(l=>{Ut(a.prototype,l,{get(){return this.$$c&&l in this.$$c?this.$$c[l]:this.$$d[l]},set(u){u=Fn(l,u,e),this.$$d[l]=u;var c=this.$$c;if(c){var f=Bt(c,l)?.get;f?c[l]=u:c.$set({[l]:u})}}})}),r.forEach(l=>{Ut(a.prototype,l,{get(){return this.$$c?.[l]}})}),t.element=a,a}var mc=new Set(["$$slots","$$events","$$legacy","$$host","loading"]),bc=ee('<div class="altcha-checkbox"><input/> <svg aria-hidden="true" width="12" height="9" viewBox="0 0 12 9"><polyline points="1 5 4 8 11 1"></polyline></svg> <div class="altcha-spinner altcha-checkbox-spinner" aria-hidden="true"></div></div>');function Ao(t,e){ut(e,!0);let n=X(e,"loading"),r=Wn(e,mc),i;function s(){i?.click()}var a={get loading(){return n()},set loading(f){n(f),q()}},l=bc(),u=fe(l);Gn(u,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),yt(u,f=>i=f,()=>i);var c=J(u,2);return Wr(2),ie(l),me(()=>P(l,"data-loading",n())),qn("click",c,s),I(t,l),ht(a)}Yn(["click"]);wt(Ao,{loading:{}},[],[],{mode:"open"});var yc=new Set(["$$slots","$$events","$$legacy","$$host","loading"]),wc=ee('<div class="altcha-checkbox-native"><input/> <div class="altcha-spinner altcha-checkbox-native-spinner"></div></div>');function xo(t,e){ut(e,!0);let n=X(e,"loading"),r=Wn(e,yc);var i={get loading(){return n()},set loading(l){n(l),q()}},s=wc(),a=fe(s);return Gn(a,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),Wr(2),ie(s),me(()=>P(s,"data-loading",n())),I(t,s),ht(i)}wt(xo,{loading:{}},[],[],{mode:"open"});var _c=ee('<div><a target="_blank" rel="noopener" class="altcha-logo" aria-hidden="true" tabindex="-1"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.33955 16.4279C5.88954 20.6586 12.1971 21.2105 16.4279 17.6604C18.4699 15.947 19.6548 13.5911 19.9352 11.1365L17.9886 10.4279C17.8738 12.5624 16.909 14.6459 15.1423 16.1284C11.7577 18.9684 6.71167 18.5269 3.87164 15.1423C1.03163 11.7577 1.4731 6.71166 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577C16.9767 5.86872 17.5322 7.02798 17.804 8.2324L19.9522 9.01429C19.7622 7.07737 19.0059 5.17558 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956C-0.658625 5.88958 -1.21046 12.1971 2.33955 16.4279Z" fill="currentColor"></path><path d="M3.57212 2.33956C1.65755 3.94607 0.496389 6.11731 0.12782 8.40523L2.04639 9.13961C2.26047 7.15832 3.21057 5.25375 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577L13.8302 6.78606L19.9633 9.13364C19.7929 7.15555 19.0335 5.20847 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956Z" fill="currentColor"></path><path d="M7 10H5C5 12.7614 7.23858 15 10 15C12.7614 15 15 12.7614 15 10H13C13 11.6569 11.6569 13 10 13C8.3431 13 7 11.6569 7 10Z" fill="currentColor"></path></svg></a></div>');function ui(t,e){ut(e,!0);let n=X(e,"strings"),r="https://altcha.org";var i={get strings(){return n()},set strings(l){n(l),q()}},s=_c(),a=fe(s);return P(a,"href",r),ie(s),me(()=>P(a,"aria-label",n().ariaLinkLabel)),I(t,s),ht(i)}wt(ui,{strings:{}},[],[],{mode:"open"});var Ec=ee('<div class="altcha-footer"><p></p> <!></div>');function jr(t,e){ut(e,!0);let n=X(e,"logo"),r=X(e,"strings");var i={get logo(){return n()},set logo(c){n(c),q()},get strings(){return r()},set strings(c){r(c),q()}},s=Ec(),a=fe(s);vo(a,()=>r().footer,!0),ie(a);var l=J(a,2);{var u=c=>{ui(c,{get strings(){return r()}})};oe(l,c=>{n()&&c(u)})}return ie(s),I(t,s),ht(i)}wt(jr,{logo:{},strings:{}},[],[],{mode:"open"});var kc=new Set(["$$slots","$$events","$$legacy","$$host","loading"]),Ac=ee('<div class="altcha-switch"><input/>  <div class="altcha-switch-toggle"><div class="altcha-spinner altcha-switch-spinner"></div></div></div>');function Co(t,e){ut(e,!0);let n=X(e,"loading"),r=Wn(e,kc),i;function s(){i?.click()}var a={get loading(){return n()},set loading(f){n(f),q()}},l=Ac(),u=fe(l);Gn(u,()=>({type:"checkbox",...r}),void 0,void 0,void 0,void 0,!0),yt(u,f=>i=f,()=>i);var c=J(u,2);return ie(l),me(()=>P(l,"data-loading",n())),qn("click",c,s),I(t,l),ht(a)}Yn(["click"]);wt(Co,{loading:{}},[],[],{mode:"open"});var pe=(t=>(t.ERROR="error",t.LOADING="loading",t.PLAYING="playing",t.PAUSED="paused",t.READY="ready",t))(pe||{}),Oo=(t=>(t.SHA_256="SHA-256",t.SHA_384="SHA-384",t.SHA_512="SHA-512",t))(Oo||{}),B=(t=>(t.CODE="code",t.ERROR="error",t.VERIFIED="verified",t.VERIFYING="verifying",t.UNVERIFIED="unverified",t.EXPIRED="expired",t))(B||{}),xc=ee('<div class="altcha-code-challenge-title"> </div>'),Cc=ee('<div class="altcha-spinner"></div>'),Oc=oi('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>'),Sc=oi('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 7C15 6.44772 15.4477 6 16 6C16.5523 6 17 6.44772 17 7V17C17 17.5523 16.5523 18 16 18C15.4477 18 15 17.5523 15 17V7ZM7 7C7 6.44772 7.44772 6 8 6C8.55228 6 9 6.44772 9 7V17C9 17.5523 8.55228 18 8 18C7.44772 18 7 17.5523 7 17V7Z"></path></svg>'),Tc=oi('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 12H7C8.10457 12 9 12.8954 9 14V19C9 20.1046 8.10457 21 7 21H4C2.89543 21 2 20.1046 2 19V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12V19C22 20.1046 21.1046 21 20 21H17C15.8954 21 15 20.1046 15 19V14C15 12.8954 15.8954 12 17 12H20C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12Z"></path></svg>'),$c=ee('<button type="button" class="altcha-button altcha-button-secondary"><!></button>'),Mc=ee('<audio hidden="" autoplay=""></audio>'),Nc=ee('<div class="altcha-code-challenge"><form data-code-challenge="true"><!> <div class="altcha-code-challenge-text"> </div> <img class="altcha-code-challenge-image" alt=""/> <div class="altcha-code-challenge-row"><input type="text" class="altcha-input" autocomplete="off" name="" required=""/> <!> <button type="button" class="altcha-button altcha-button-secondary"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 9.25022 5.38734 6.82447 7.50024 5.38451L7.5 8H9.5V2L3.5 2V4L5.99918 3.99989C3.57075 5.82434 2 8.72873 2 12Z"></path></svg></button></div> <div class="altcha-code-challenge-buttons"><button type="submit" class="altcha-button"> </button> <button type="button" class="altcha-button altcha-button-secondary"> </button></div></form> <!></div>');function So(t,e){ut(e,!0);let n=X(e,"audioUrl"),r=X(e,"codeChallenge"),i=X(e,"config"),s=X(e,"imageUrl"),a=X(e,"onCancel"),l=X(e,"onReload"),u=X(e,"onSubmit"),c=X(e,"strings"),f=M(void 0),d=M(void 0),v=M(void 0),g=M(!1),m=M(""),k=M(!1);ai(()=>(i().disableAutoFocus||xt().then(()=>{o(v)?.focus()}),()=>{o(d)&&(o(d).pause(),w(d,void 0))}));function N(){w(f,pe.PAUSED,!0)}function H(x){w(f,pe.ERROR,!0)}function V(){w(f,pe.READY,!0)}function ne(){w(f,pe.LOADING,!0)}function se(){w(f,pe.PLAYING,!0)}function K(){w(f,pe.PAUSED,!0)}function Ye(x){x.code==="Space"?(x.preventDefault(),x.stopPropagation(),Z()):x.code==="Escape"&&(x.preventDefault(),x.stopPropagation(),a()?.())}function E(x){x.preventDefault(),x.stopPropagation(),u()?.(o(m))}function L(x){x.play().catch(Y=>{if(!(Y instanceof DOMException&&Y.name==="AbortError"))throw Y})}function Z(){o(d)?o(f)===pe.LOADING||(o(d).paused?(n()&&o(d).src!==n()&&(o(d).src=n()),o(d).currentTime=0,L(o(d))):o(d).pause()):(w(k,!0),requestAnimationFrame(()=>{o(d)&&n()&&(o(d).src=n(),L(o(d)))}))}var j={get audioUrl(){return n()},set audioUrl(x){n(x),q()},get codeChallenge(){return r()},set codeChallenge(x){r(x),q()},get config(){return i()},set config(x){i(x),q()},get imageUrl(){return s()},set imageUrl(x){s(x),q()},get onCancel(){return a()},set onCancel(x){a(x),q()},get onReload(){return l()},set onReload(x){l(x),q()},get onSubmit(){return u()},set onSubmit(x){u(x),q()},get strings(){return c()},set strings(x){c(x),q()}},R=Nc(),Ce=fe(R),ge=fe(Ce);{var Me=x=>{var Y=xc(),Et=Rt(Y,!0);me(()=>Xe(Et,c().verificationRequired)),I(x,Y)};oe(ge,x=>{i().codeChallengeDisplay!=="standard"&&x(Me)})}var W=J(ge,2),ot=Rt(W,!0),A=J(W,2),ue=J(A,2),Q=fe(ue);li(Q),Q.disabled=o(g),yt(Q,x=>w(v,x),()=>o(v));var b=J(Q,2);{var Wt=x=>{var Y=$c(),Et=fe(Y);{var en=ye=>{var We=Cc();I(ye,We)},or=ye=>{var We=Oc();I(ye,We)},ar=ye=>{var We=Sc();I(ye,We)},lr=ye=>{var We=Tc();I(ye,We)};oe(Et,ye=>{o(f)===pe.LOADING?ye(en):o(f)===pe.ERROR?ye(or,1):o(f)===pe.PLAYING?ye(ar,2):ye(lr,-1)})}ie(Y),me(()=>{P(Y,"title",c().getAudioChallenge),Y.disabled=o(f)===pe.LOADING||o(f)===pe.ERROR,P(Y,"aria-label",o(f)===pe.LOADING?c().loading:c().getAudioChallenge)}),ae("click",Y,()=>Z(),!0),I(x,Y)};oe(b,x=>{r().audio&&x(Wt)})}var Jt=J(b,2);ie(ue);var Ge=J(ue,2),Xt=fe(Ge),xn=Rt(Xt,!0),_t=J(Xt,2),Zt=Rt(_t,!0);ie(Ge),ie(Ce);var Qt=J(Ce,2);{var Ee=x=>{var Y=Mc();yt(Y,Et=>w(d,Et),()=>o(d)),ae("error",Y,H),ae("loadstart",Y,ne),ae("canplay",Y,V),ae("pause",Y,K),ae("playing",Y,se),ae("ended",Y,N),I(x,Y)};oe(Qt,x=>{o(k)&&x(Ee)})}return ie(R),me(()=>{Xe(ot,c().enterCodeFromImage),P(A,"src",s()),P(Q,"minlength",r().length||1),P(Q,"maxlength",r().length),P(Q,"placeholder",c().enterCode),P(Q,"aria-label",o(f)===pe.LOADING?c().loading:o(f)===pe.PLAYING?"":c().enterCodeAria),P(Q,"aria-live",o(f)?"assertive":"polite"),P(Q,"aria-busy",o(f)===pe.LOADING),P(Jt,"title",c().reload),P(Jt,"aria-label",c().reload),P(Xt,"aria-label",c().verify),Xe(xn,c().verify),P(_t,"aria-label",c().cancel),Xe(Zt,c().cancel)}),ae("submit",Ce,E,!0),qn("keydown",Q,Ye),dc(Q,()=>o(m),x=>w(m,x)),ae("click",Jt,()=>l()?.(),!0),ae("click",_t,()=>a()?.(),!0),I(t,R),ht(j)}Yn(["keydown"]);wt(So,{audioUrl:{},codeChallenge:{},config:{},imageUrl:{},onCancel:{},onReload:{},onSubmit:{},strings:{}},[],[],{mode:"open"});var Fc=new Set(["$$slots","$$events","$$legacy","$$host","anchor","children","display","backdrop","onClickOutside","onClickOutsideDelay","onClose","placement","updateUISignal","variant"]),Lc=ee('<div class="altcha-popover-backdrop" data-backdrop=""></div>'),Ic=ee('<div class="altcha-popover-arrow"></div>'),Dc=ee('<div role="button" class="altcha-popover-close">&times;</div>'),Rc=ee('<!> <div><!> <!> <div class="altcha-popover-content"><!></div></div>',1);function Ur(t,e){ut(e,!0);let n=X(e,"anchor"),r=X(e,"children"),i=X(e,"display",7,"standard"),s=X(e,"backdrop",7,!1),a=X(e,"onClickOutside"),l=X(e,"onClickOutsideDelay",7,600),u=X(e,"onClose"),c=X(e,"placement",7,"auto"),f=X(e,"updateUISignal"),d=X(e,"variant",7,"neutral"),v=Wn(e,Fc),g=M(void 0),m=M(void 0),k=M(!1),N=M(0);ke(()=>{c()!=="auto"&&w(k,c()==="top")}),ke(()=>{f()&&K()}),ai(()=>{let A=i()==="bottomsheet"||i()==="overlay";return A&&(o(m)&&document.body.append(o(m)),o(g)&&document.body.append(o(g))),K(),xt().then(()=>{w(N,Date.now(),!0)}),()=>{A&&(o(m)&&document.body.removeChild(o(m)),o(g)&&document.body.removeChild(o(g)))}});function H(){u()?.()}function V(A){let ue=A.target;!o(g)?.contains(ue)&&(!l()||o(N)+l()<Date.now())&&a()?.()}function ne(){K()}function se(){K()}function K(){if(n()&&c()==="auto"&&o(g)){let A=n().getBoundingClientRect(),Q=document.documentElement.clientHeight-(A.top+A.height)<o(g).clientHeight;o(k)!==Q&&w(k,Q)}}var Ye={get anchor(){return n()},set anchor(A){n(A),q()},get children(){return r()},set children(A){r(A),q()},get display(){return i()},set display(A="standard"){i(A),q()},get backdrop(){return s()},set backdrop(A=!1){s(A),q()},get onClickOutside(){return a()},set onClickOutside(A){a(A),q()},get onClickOutsideDelay(){return l()},set onClickOutsideDelay(A=600){l(A),q()},get onClose(){return u()},set onClose(A){u(A),q()},get placement(){return c()},set placement(A="auto"){c(A),q()},get updateUISignal(){return f()},set updateUISignal(A){f(A),q()},get variant(){return d()},set variant(A="neutral"){d(A),q()}},E=Rc();ae("click",Ct,V,!0),ae("resize",Ct,ne),ae("scroll",Ct,se);var L=Dt(E);{var Z=A=>{var ue=Lc();yt(ue,Q=>w(m,Q),()=>o(m)),I(A,ue)};oe(L,A=>{s()&&A(Z)})}var j=J(L,2);Gn(j,()=>({...v,class:`altcha-popover ${(e.class||"")??""}`,"data-popover":!0,"data-variant":d(),"data-top":o(k),"data-display":i()}));var R=fe(j);{var Ce=A=>{var ue=Ic();I(A,ue)};oe(R,A=>{i()==="standard"&&A(Ce)})}var ge=J(R,2);{var Me=A=>{var ue=Dc();ae("click",ue,H,!0),I(A,ue)};oe(ge,A=>{i()!=="standard"&&A(Me)})}var W=J(ge,2),ot=fe(W);return Wl(ot,()=>r()??vt),ie(W),ie(j),yt(j,A=>w(g,A),()=>o(g)),I(t,E),ht(Ye)}wt(Ur,{anchor:{},children:{},display:{},backdrop:{},onClickOutside:{},onClickOutsideDelay:{},onClose:{},placement:{},updateUISignal:{},variant:{}},[],[],{mode:"open"});function Pc(t){return Array.from(new Uint8Array(t)).map(e=>e.toString(16).padStart(2,"0")).join("")}function Bc(t,e="altcha-css",n){if(typeof document<"u"&&document&&!document.getElementById(e)){let r=document.createElement("style");r.id=e,r.textContent=t;let i=document.currentScript?.nonce??document.querySelector('meta[name="csp-nonce"]')?.content;i&&(r.nonce=i),document.head.appendChild(r)}}Object.values(Oo);async function To(t){let{challenge:e,concurrency:n=navigator.hardwareConcurrency,controller:r=new AbortController,createWorker:i,onOutOfMemory:s=v=>v>1?Math.floor(v/2):0,counterMode:a,timeout:l}=t,u=Math.min(16,Math.max(1,n)),c=[],f=()=>{for(let v of c)v.terminate()};for(let v=0;v<u;v++)c.push(await i(e.parameters.algorithm));let d=null;try{d=await Promise.race(c.map((v,g)=>(r.signal.addEventListener("abort",()=>{v.postMessage({type:"abort"})}),new Promise((m,k)=>{v.addEventListener("error",N=>{k(N)}),v.addEventListener("message",N=>{if(N.data){for(let H of c)H!==v&&H.postMessage({type:"abort"});if(N.data.error)return k(new Error(N.data.error))}m(N.data)}),v.postMessage({challenge:e,counterMode:a,counterStart:g,counterStep:u,timeout:l,type:"work"})}))))}catch(v){if(v instanceof Error&&!!v?.message?.includes("Out of memory")&&s){f();let m=s(u);if(m)return To({...t,challenge:e,controller:r,concurrency:m,createWorker:i})}throw v}finally{f()}return r.signal.aborted?null:d||null}var Kr=class{TAG_CODES={INPUT:1,TEXTAREA:2,SELECT:3,BUTTON:4,A:5,DETAILS:6,SUMMARY:7,IFRAME:8,VIDEO:9,AUDIO:10};maxSamples;sampleInterval;target;focusStartTime=0;focusInteraction=0;focusInteractionTimer=null;lastPointerSample=0;lastTouchSample=0;lastScrollSample=0;pendingPointer=null;pendingTouch=null;focus=[];pointer=[];scroll=[];touch=[];constructor(e={}){let{maxSamples:n=60,sampleInterval:r=50,target:i=window}=e;this.maxSamples=n,this.sampleInterval=r,this.target=i,this.attach()}destroy(){let e={capture:!0};this.target.removeEventListener("focusin",this.onFocus,e),this.target.removeEventListener("keydown",this.onInteraction,e),this.target.removeEventListener("pointerdown",this.onInteraction,e),this.target.removeEventListener("pointermove",this.onPointer,e),this.target.removeEventListener("scroll",this.onScroll,e),this.target.removeEventListener("touchmove",this.onTouchMove,e)}export(){return{focus:this.focus,maxTouchPoints:navigator.maxTouchPoints||0,pointer:this.pointer,scroll:this.scroll,time:Date.now(),touch:this.touch}}attach(){let e={passive:!0,capture:!0};this.target.addEventListener("focusin",this.onFocus,e),this.target.addEventListener("keydown",this.onInteraction,e),this.target.addEventListener("pointerdown",this.onInteraction,e),this.target.addEventListener("pointermove",this.onPointer,e),this.target.addEventListener("scroll",this.onScroll,e),this.target.addEventListener("touchmove",this.onTouchMove,e)}evict(e){e.length>this.maxSamples&&e.splice(0,e.length-this.maxSamples)}onFocus=e=>{if(this.focusInteraction===2)return;let n=e.target;if(!(n instanceof Element))return;let r=performance.now();this.focusStartTime===0&&(this.focusStartTime=r),this.focus.push([Math.round(r-this.focusStartTime),n.tabIndex,this.TAG_CODES[n.tagName]??0,this.focusInteraction?1:0]),this.evict(this.focus)};onInteraction=e=>{this.focusInteraction="keyCode"in e?1:2,this.focusInteractionTimer&&clearTimeout(this.focusInteractionTimer),this.focusInteractionTimer=setTimeout(()=>{this.focusInteraction=0},100)};onPointer=e=>{if(e.pointerType==="touch")return;let n=e.timeStamp||performance.now();this.pendingPointer=[Math.round(e.clientX),Math.round(e.clientY),Math.round(n)],n-this.lastPointerSample>=this.sampleInterval&&(this.pointer.push(this.pendingPointer),this.lastPointerSample=n,this.pendingPointer=null,this.evict(this.pointer))};onScroll=()=>{let e=performance.now();e-this.lastScrollSample<this.sampleInterval||(this.scroll.push([Math.round(window.scrollY),Math.round(e)]),this.lastScrollSample=e,this.evict(this.scroll))};onTouchMove=e=>{let n=e.timeStamp||performance.now(),r=e.touches[0];r&&(this.pendingTouch=[Math.round(r.clientX),Math.round(r.clientY),Math.round(n),Math.round(r.force*1e3)/1e3,Math.round(r.radiusX||0),Math.round(r.radiusY||0)],n-this.lastTouchSample>=this.sampleInterval&&(this.touch.push(this.pendingTouch),this.lastTouchSample=n,this.pendingTouch=null,this.evict(this.touch)))}},Vc=ee('<div class="altcha-overlay-backdrop" data-backdrop=""></div>'),jc=ee('<div class="altcha-overlay-content"></div>'),Uc=ee('<div role="button" class="altcha-overlay-close">&times;</div> <!>',1),Kc=ee('<div class="altcha-floating-arrow"></div>'),Hc=ee('<input type="hidden"/>'),zc=ee('<div class="altcha-error">Secure context (HTTPS) required.</div>'),vs=ee('<div class="altcha-error"> </div>'),qc=ee("<!> <!>",1),Yc=ee('<!> <div class="altcha"><!> <div class="altcha-main"><div><div class="altcha-checkbox-wrap"><!> <label><!></label></div> <!></div> <!> <!> <!></div> <!></div>',1);function Gc(t,e){ut(e,!0);let n=()=>es(f,"$altchaDefaults",i),r=()=>es(m,"$altchaI18nStore",i),[i,s]=pl(),a='input[type="text"]:not([data-no-spamfilter]), textarea:not([data-no-spamfilter])',l='input[type="submit"], button[type="submit"], button:not([type="button"]):not([type="reset"])',u=["ar","fa","he","ur"],{isSecureContext:c}=globalThis,{store:f}=globalThis.$altcha.defaults,d=navigator.hardwareConcurrency||2,v=navigator.deviceMemory||0,g=v&&v<=4?Math.min(4,d):d,m=globalThis.$altcha.i18n.store,k=e.$$host,N=(h,p)=>{xt().then(()=>{k?.dispatchEvent(new CustomEvent(h,{detail:p}))})},H=null,V=M(lt(new URL(location.origin))),ne=M(!1),se=M(null),K=M(null),Ye=M(null),E=M(lt(B.UNVERIFIED)),L=M(void 0),Z=M(void 0),j=M(null),R=M(void 0),Ce=M(null),ge=M(null),Me=M(null),W=M(null),ot=M(lt([])),A=M(0),ue=M(lt({})),Q=M(!0),b=we(()=>({fetch:(h,p)=>fetch(h,p),audioChallengeLanguage:"",auto:"off",barPlacement:"bottom",challenge:"",codeChallenge:null,codeChallengeDisplay:"standard",credentials:null,debug:!1,disableAutoFocus:!1,display:"standard",floatingAnchor:"",floatingOffset:8,floatingPersist:!1,floatingPlacement:"auto",hideFooter:!1,hideLogo:!1,humanInteractionSignature:!0,language:"",mockError:!1,minDuration:500,overlayContent:"",name:"altcha",popoverPlacement:"auto",retryOnOutOfMemoryError:!0,setCookie:null,serverVerificationFields:!1,serverVerificationTimeZone:!1,test:!1,timeout:9e4,type:"checkbox",validationMessage:"",verifyFunction:null,verifyUrl:"",workers:g,...n(),...o(ue)})),Wt=we(()=>`altcha-checkbox-${e.id||Math.floor(Math.random()*1e12).toString(16)}`),Jt=we(()=>Go(o(b).type)),Ge=we(()=>o(b).auto),Xt=we(()=>o(E)===B.VERIFYING),xn=we(()=>!o(b).hideFooter),_t=we(()=>!o(b).hideLogo&&o(b).display!=="bar"),Zt=we(()=>Wo(r(),[o(b).language,document.documentElement.lang,...navigator.languages])),Qt=we(()=>u.includes(o(Zt).language)?"rtl":void 0),Ee=we(()=>({...o(Zt).strings})),x=we(()=>o(se)?.audio?.match(/^(https?:)?\//)?Cn(o(se).audio,o(V),{language:o(b).audioChallengeLanguage||o(Zt).language}).toString():o(se)?.audio),Y=we(()=>o(se)?.image?.match(/^(https?:)?\//)?Cn(o(se).image,o(V)):o(se)?.image);ke(()=>{tn({auto:e.auto,challenge:e.challenge,display:e.display,language:e.language,name:e.name,type:e.type,workers:e.workers})}),ke(()=>{e.theme?k?.setAttribute("theme",e.theme):k?.removeAttribute("theme")}),ke(()=>{if(e.configuration)try{tn(JSON.parse(e.configuration))}catch{z("unable to parse the `configuration` attribute (JSON expected)")}}),ke(()=>{o(Ye)!==o(b).display&&On(o(b).display)}),ke(()=>{o(ne)&&o(E)===B.VERIFYING&&w(ne,!1)}),ke(()=>{!o(ne)&&o(E)===B.VERIFIED&&w(ne,!0)}),ke(()=>{if(!o(ne)){let h=cr();h&&h.checked&&(h.checked=!1)}}),ke(()=>{o(E)===B.VERIFIED&&cr()?.setCustomValidity("")}),ke(()=>{if(o(Ge)==="onload"){let h=setTimeout(()=>{Ft()},10);return()=>{h&&clearTimeout(h)}}}),ke(()=>{o(ge)&&z("error:",o(ge))}),ke(()=>{o(W)&&o(b).setCookie&&ua(o(W),o(b).setCookie)}),ai(()=>(z("mounted","3.5.0"),k&&globalThis.$altcha.instances.add(k),w(j,Xo(),!0),o(j)?.addEventListener("reset",Vi),o(j)?.addEventListener("submit",ji,{capture:!0}),document.addEventListener("focusin",Bi),Et(),o(b).humanInteractionSignature&&(z("human interaction signature enabled"),H=new Kr),N("load"),c||z("secure context (HTTPS) required"),()=>{or(),k&&globalThis.$altcha.instances.delete(k),o(Me)&&clearTimeout(o(Me)),o(j)?.removeEventListener("reset",Vi),o(j)?.removeEventListener("submit",ji,{capture:!0}),document.removeEventListener("focusin",Bi),H?.destroy()}));function Et(){w(ot,[...globalThis.$altcha.plugins].map(h=>new h(k)),!0),z("activating plugins",o(ot).map(h=>h.constructor.name));for(let h of o(ot))h.activate()}async function en(h,...p){let y;for(let _ of o(ot))y=await _[h].call(_,...p);return y}function or(){for(let h of o(ot))h.destroy()}function ar(h){let[p,y]=h.salt.split("?"),_={};if(y)try{Object.assign(_,Object.fromEntries(new URLSearchParams(y).entries()))}catch{}let O={codeChallenge:h.codeChallenge,parameters:{algorithm:h.algorithm,cost:1,data:_,expiresAt:_?.expires?parseInt(_.expires,10):void 0,keyLength:h.algorithm==="SHA-512"?64:h.algorithm==="SHA-384"?48:32,nonce:Pc(new TextEncoder().encode(h.salt)),keyPrefix:h.challenge,salt:""},signature:h.signature};return Object.defineProperties(O,{_originalSalt:{enumerable:!1,value:h.salt,writable:!1},_version:{enumerable:!1,value:1,writable:!1}}),O}function lr(h,p){return{algorithm:h.parameters.algorithm,challenge:h.parameters.keyPrefix,number:p.counter,salt:"_originalSalt"in h?h._originalSalt:h.parameters.nonce,signature:h.signature,took:p.time||0}}async function ye(h){await new Promise(p=>setTimeout(p,h))}async function We(h=o(b).challenge,p){let y=await en("onFetchChallenge",h),_=null;if(y!==void 0)return y;if(typeof h=="string")if(h.startsWith("{")){z("parsing JSON challenge");try{_=JSON.parse(h)}catch{throw new Error("Unable to parse JSON challenge.")}}else{z("fetching challenge from",p?.method||"GET",h),w(V,new URL(h,location.origin),!0);let O=await o(b).fetch(h,{credentials:o(b).credentials||void 0,...p});await Ki(O);let C=O.headers.get("x-altcha-config");C&&aa(C);let U=await O.json();if(U&&"his"in U&&U.his){if(z("requested HIS"),!H)throw new Error("Server requested HIS data but collector is disabled.");return We(Cn(U.his.url,o(V)),{body:JSON.stringify({his:H.export()}),headers:{"content-type":"application/json"},method:"POST"})}U&&"hisResult"in U&&U.hisResult&&z("HIS result",U.hisResult),_=U}else if(h&&typeof h=="object")try{_=JSON.parse(JSON.stringify(h))}catch{throw new Error("Unable to parse JSON challenge.")}if(qo(_)&&(_=ar(_)),!Yo(_))throw new Error("Challenge validation failed.");return _}function qo(h){return h!==null&&typeof h=="object"&&"challenge"in h}function Yo(h){return!!h&&typeof h=="object"&&"parameters"in h&&!!h.parameters&&typeof h.parameters=="object"&&"algorithm"in h.parameters&&"nonce"in h.parameters&&"salt"in h.parameters&&"keyPrefix"in h.parameters}function cr(){return document.getElementById(o(Wt))}function Go(h){switch(h){case"checkbox":return Ao;case"switch":return Co;default:return xo}}function Wo(h,p){let y=Object.keys(h).map(O=>O.toLowerCase()),_=p.reduce((O,C)=>(C=C.toLowerCase(),O||(h[C]?C:null)||y.find(U=>C.split("-")[0]===U.split("-")[0])||null),null);return h[_||""]||(_="en"),{language:_,strings:h[_]}}function Jo(h){switch(h){case"bar":return o(b).barPlacement||"bottom";case"floating":return o(b).floatingPlacement||"auto";default:return}}function Xo(){if(e.form){let p=k?.getRootNode()?.getElementById?.(e.form);return p instanceof HTMLFormElement?p:null}return o(R)?.closest("form")??null}function Pi(h){return o(j)?Array.from(o(j).elements).filter(p=>p.matches(h)):[]}function Zo(h){return Pi(a).reduce((y,_)=>{let O=_.name,C=_.value;return O&&C&&(y[O]=/\n/.test(C)?C.replace(new RegExp("(?<!\\r)\\n","g"),`\r
`):C),y},{})}function Qo(){try{return Intl.DateTimeFormat().resolvedOptions().timeZone}catch{}}function Cn(h,p,y){let _=new URL(h,p);if(_.search||(_.search=p.search),y)for(let O in y)y[O]!==void 0&&y[O]!==null&&_.searchParams.set(O,y[O]);return _.toString()}function ea(h){!o(ne)&&h.currentTarget.checked?(h.preventDefault(),h.currentTarget.checked=!1,o(E)!==B.VERIFYING&&Ft()):h.currentTarget.checked||(h.preventDefault(),Ne())}function ta(h){o(E)===B.VERIFYING?h.currentTarget.setCustomValidity(o(Ee).waitAlert):o(b).validationMessage&&h.currentTarget.setCustomValidity(o(b).validationMessage)}function na(){On(o(b).display),Ne()}function ra(){Sn()}function ia(h){let p=h.target;o(b).display==="floating"&&p&&!k?.contains(p)&&!p.hasAttribute("data-backdrop")&&!p.closest("[data-popover]")&&o(E)!==B.VERIFIED&&!o(b).floatingPersist&&ur()}function Bi(h){let p=h.target;o(Ge)==="onfocus"&&o(E)===B.UNVERIFIED&&o(j)&&p instanceof Element&&(o(j).contains(p)||p.form===o(j))&&Ft()}function Vi(){On(o(b).display),Ne()}function ji(h){h.target?.getAttribute("data-code-challenge")!=="true"&&o(Ge)==="onsubmit"&&o(E)===B.UNVERIFIED&&(h.preventDefault(),h.stopPropagation(),w(Ce,h.submitter,!0),hr(),Ft().then(y=>{y&&!o(se)&&xt().then(()=>{Ui(o(Ce))})}))}function sa(h){h.persisted&&(On(o(b).display),Ne())}function oa(){Sn()}function aa(h){try{let p=JSON.parse(h);p&&typeof p=="object"&&tn({serverVerificationFields:p?.sentinel?.fields,serverVerificationTimeZone:p?.sentinel?.timeZone,verifyUrl:p.verifyurl,...p})}catch(p){z("unable to configure from x-altcha-config header",p)}}function la(h=20){if(!o(R))return;let p=o(b).floatingPlacement;if(!o(Z)&&(w(Z,(o(b).floatingAnchor instanceof HTMLElement?o(b).floatingAnchor:o(b).floatingAnchor?document.querySelector(o(b).floatingAnchor):Pi(l)[0])||o(j),!0),!o(Z))){z("unable to find floating anchor element");return}let y=parseInt(o(b).floatingOffset,10)||12,_=o(Z).getBoundingClientRect(),O=o(R).getBoundingClientRect(),C=document.documentElement.clientHeight,U=document.documentElement.clientWidth,Oe=!p||p==="auto"?_.bottom+O.height+y+h>C:p==="top",G=Math.max(h,Math.min(U-h-O.width,_.left+_.width/2-O.width/2));if(o(R).style.setProperty("--altcha-floating-left",`${G}px`),o(R).style.setProperty("--altcha-floating-top",Oe?`${_.top-(O.height+y)}px`:`${_.bottom+y}px`),o(R).setAttribute("data-floating-position",Oe?"top":"bottom"),o(L)){let he=o(L).getBoundingClientRect();o(L).style.left=_.left-G+_.width/2-he.width/2+"px"}}async function ca(h,p){let y=await en("onRequestServerVerification",h,p);if(y!==void 0)return y;if(z("requesting server verification from",o(b).verifyUrl),!o(b).verifyUrl)throw new Error("Parameter verifyUrl must be set for server verification.");let _=await o(b).fetch(Cn(o(b).verifyUrl,o(V)),{body:JSON.stringify({code:p,fields:o(b).serverVerificationFields?Zo():void 0,payload:h,timeZone:o(b).serverVerificationTimeZone?Qo():void 0}),credentials:o(b).credentials||void 0,headers:{"Content-Type":"application/json"},method:"POST"});await Ki(_);let O=await _.json();return O&&typeof O=="object"&&"payload"in O&&O.payload&&N("serververification",O),O}function Ui(h){o(j)&&"requestSubmit"in o(j)?o(j).requestSubmit(h):o(j)?.reportValidity()&&(h?h.click():o(j).submit())}function ua(h,p={}){let{domain:y,name:_=o(b).name,maxAge:O,path:C,sameSite:U,secure:Oe}=p,G=`${encodeURIComponent(_)}=${encodeURIComponent(h)}`;y&&(G+=`; Domain=${y}`),O!=null&&(G+=`; Max-Age=${O}`),C&&(G+=`; Path=${C}`),U&&(G+=`; SameSite=${U}`),Oe&&(G+="; Secure"),document.cookie=G}function On(h){switch(h){case"bar":case"floating":case"overlay":ur(),(!o(Ge)||o(Ge)==="off")&&(o(ue).auto="onsubmit");break;case"standard":hr()}o(Ye)!==h&&w(Ye,h,!0)}function ha(h){o(Me)&&clearTimeout(o(Me));let p=()=>{o(E)!==B.UNVERIFIED?(w(ne,!1),Fe(B.EXPIRED)):Ne(),N("expired")},y=h*1e3-Date.now();y>=1?w(Me,setTimeout(p,y),!0):p()}async function Ki(h){if(h.status>=400){if(h.headers.get("content-type")?.includes("/json")){let y;try{y=await h.json()}catch{}if(y&&"error"in y)throw new Error(`Server responded with ${h.status} - ${y.error}`)}throw new Error(`Server responded with ${h.status}.`)}let p=h.headers.get("content-type");if(!p||!p.includes("/json"))throw new Error(`Server responded with invalid content-type. Expected application/json, received ${p}.`)}async function Hi(h){if(!o(W)){Fe(B.ERROR,"Cannot verify code challenge without PoW payload.");return}Fe(B.VERIFYING);let p=null;if(o(b).verifyUrl)p=await ca(o(W),h);else if(o(b).verifyFunction)p=await o(b).verifyFunction(o(W),h);else{Fe(B.ERROR,"Parameter verifyUrl is required for code challenge verification.");return}p?.payload&&(w(W,p.payload,!0),z("server payload",o(W))),p?.verified===!0?(z("verified"),Fe(B.VERIFIED),N("verified",{payload:o(W)}),o(Ge)==="onsubmit"&&xt().then(()=>{Ui(o(Ce))})):Fe(B.ERROR,p?.reason||"Verification failed."),o(b).disableAutoFocus||cr()?.focus()}function tn(h){Object.assign(o(ue),{...Object.fromEntries(Object.entries(h).filter(([p,y])=>y!==void 0))})}function fa(){return{...o(b)}}function da(){return o(E)}function ur(){w(Q,!1)}function z(...h){(o(b).debug||h.some(p=>p instanceof Error))&&console[h[0]instanceof Error?"error":"log"]("ALTCHA",`[name=${o(b).name}]`,...h)}function Ne(h=B.UNVERIFIED,p=null){w(ne,!1),w(ge,p,!0),w(W,null),o(K)&&o(K).abort(),o(Me)&&(clearTimeout(o(Me)),w(Me,null)),Fe(h)}function Fe(h,p=null){w(E,h,!0),w(ge,p,!0),N("statechange",{payload:o(W),state:o(E)})}function hr(){w(Q,!0),xt().then(()=>{Sn()})}function Sn(){if(o(b).display==="floating")return la();w(A,o(A)+1)}async function Ft(h={}){let{concurrency:p=Math.max(1,o(b).workers),controller:y=new AbortController,minDuration:_=o(b).minDuration}=h,O=performance.now(),C=null,U=null,Oe=!1,G=await en("onVerify",h);if(G!==void 0)return G;Ne(B.VERIFYING),w(K,y,!0);try{if(!c)throw new Error("Secure context (HTTPS) required.");if(o(b).mockError)throw new Error("Mock error.");if(o(b).test)return z("running test mode with null challenge"),await ye(Math.max(0,_-(performance.now()-O))),o(K)?.signal.aborted?(Ne(),null):(w(W,btoa(JSON.stringify({challenge:null,solution:null,test:!0})),!0),z("verified"),Fe(B.VERIFIED),N("verified",{payload:o(W)}),{payload:o(W)});if(C=await We(),!C)throw new Error("Failed to fetch challenge.");z("challenge",C),"configuration"in C&&(z("re-configuring from challenge",C.configuration),tn(C.configuration)),C.parameters.expiresAt&&ha(C.parameters.expiresAt),Oe="_version"in C&&C._version===1;let he=globalThis.$altcha.algorithms.get(C.parameters.algorithm);if(!he)throw new Error(`Unsupported algorithm ${C.parameters.algorithm}.`);if(U=await To({challenge:C,concurrency:p,controller:y,createWorker:he,counterMode:Oe?"string":"uint32",onOutOfMemory:dt=>{if(z("out of memory error received"),N("outofmemory"),o(b).retryOnOutOfMemoryError&&dt>1){let pt=Math.floor(dt/2);return z(`retrying with ${pt} workers...`),pt}},timeout:o(b).timeout}),o(K)?.signal.aborted)return Ne(),null;if(!U)throw new Error("Failed to find solution.");z("solution",U),await ye(Math.max(0,_-(performance.now()-O))),w(se,C.codeChallenge||o(b).codeChallenge||null,!0),Oe?w(W,btoa(JSON.stringify(lr(C,U))),!0):w(W,btoa(JSON.stringify({challenge:{parameters:C.parameters,signature:C.signature},solution:U})),!0),o(se)?(z("requesting code verification"),Fe(B.CODE),N("codechallenge",{codeChallenge:o(se)})):o(b).verifyUrl?await Hi():(z("verified"),Fe(B.VERIFIED),N("verified",{payload:o(W)}))}catch(he){return z("verification failed",he),Fe(B.ERROR,String(he)),null}finally{w(K,null)}return{challenge:C,payload:o(W),solution:U}}var pa={configure:tn,getConfiguration:fa,getState:da,hide:ur,log:z,reset:Ne,setState:Fe,show:hr,updateUI:Sn,verify:Ft},zi=Yc();ae("scroll",Tr,ra),ae("click",Tr,ia),ae("pageshow",Ct,sa),ae("resize",Ct,oa);var qi=Dt(zi);{var va=h=>{var p=Vc();I(h,p)};oe(qi,h=>{o(b).display==="overlay"&&o(Q)&&h(va)})}var Je=J(qi,2),Yi=fe(Je);{var ga=h=>{var p=Uc(),y=Dt(p),_=J(y,2);{var O=C=>{var U=jc();vo(U,()=>document.querySelector(o(b).overlayContent)?.innerHTML,!0),ie(U),I(C,U)};oe(_,C=>{o(b).overlayContent&&C(O)})}ae("click",y,na,!0),I(h,p)};oe(Yi,h=>{o(b).display==="overlay"&&o(Q)&&h(ga)})}var fr=J(Yi,2),dr=fe(fr),pr=fe(dr),Gi=fe(pr);{let h=we(()=>o(b).display==="standard"&&o(Ge)!=="onsubmit"||o(E)===B.VERIFYING);Zl(Gi,()=>o(Jt),(p,y)=>{y(p,{get id(){return o(Wt)},get form(){return e.form},name:"",get required(){return o(h)},get loading(){return o(Xt)},get checked(){return o(ne)},onchange:ea,oninvalid:ta})})}var vr=J(Gi,2),ma=fe(vr);{var ba=h=>{var p=Tn();me(()=>Xe(p,o(Ee).verificationRequired)),I(h,p)},ya=h=>{var p=Tn();me(()=>Xe(p,o(Ee).verifying)),I(h,p)},wa=h=>{var p=Tn();me(()=>Xe(p,o(Ee).verified)),I(h,p)},_a=h=>{var p=Tn();me(()=>Xe(p,o(Ee).label)),I(h,p)};oe(ma,h=>{o(E)===B.CODE&&o(se)?h(ba):o(E)===B.VERIFYING?h(ya,1):o(E)===B.VERIFIED?h(wa,2):h(_a,-1)})}ie(vr),ie(pr);var Ea=J(pr,2);{var ka=h=>{ui(h,{get strings(){return o(Ee)}})};oe(Ea,h=>{o(_t)&&h(ka)})}ie(dr);var Wi=J(dr,2);{var Aa=h=>{{let p=we(()=>o(b).display==="bar"&&o(_t));jr(h,{get logo(){return o(p)},get strings(){return o(Ee)}})}};oe(Wi,h=>{o(xn)&&h(Aa)})}var Ji=J(Wi,2);{var xa=h=>{var p=Kc();yt(p,y=>w(L,y),()=>o(L)),I(h,p)};oe(Ji,h=>{o(b).display==="floating"&&h(xa)})}var Ca=J(Ji,2);{var Oa=h=>{var p=Hc();li(p),me(()=>{P(p,"form",e.form),P(p,"name",o(b).name),hc(p,o(W))}),I(h,p)};oe(Ca,h=>{o(b).setCookie||h(Oa)})}ie(fr);var Sa=J(fr,2);{var Ta=h=>{Ur(h,{get anchor(){return o(R)},onClickOutside:()=>{c&&Ne()},get placement(){return o(b).popoverPlacement},role:"alert",variant:"error",get dir(){return o(Qt)},get updateUISignal(){return o(A)},children:(p,y)=>{var _=cs(),O=Dt(_);{var C=G=>{var he=zc();I(G,he)},U=G=>{var he=vs(),dt=Rt(he,!0);me(()=>Xe(dt,o(Ee).expired)),I(G,he)},Oe=G=>{var he=vs(),dt=Rt(he,!0);me(()=>{P(he,"title",o(ge)),Xe(dt,o(Ee).error)}),I(G,he)};oe(O,G=>{!o(ge)&&!c?G(C):!o(ge)&&o(E)===B.EXPIRED?G(U,1):G(Oe,-1)})}I(p,_)},$$slots:{default:!0}})},$a=h=>{var p=cs(),y=Dt(p);Xl(y,()=>o(se),_=>{{let O=we(()=>o(b).codeChallengeDisplay!=="standard");Ur(_,{get anchor(){return o(R)},get backdrop(){return o(O)},get display(){return o(b).codeChallengeDisplay},onClose:()=>{Ne()},get placement(){return o(b).popoverPlacement},role:"dialog",get"aria-label"(){return o(Ee).verificationRequired},get dir(){return o(Qt)},get updateUISignal(){return o(A)},children:(C,U)=>{var Oe=qc(),G=Dt(Oe);So(G,{get audioUrl(){return o(x)},get imageUrl(){return o(Y)},onCancel:()=>Ne(),onReload:()=>Ft(),onSubmit:pt=>Hi(pt),get codeChallenge(){return o(se)},get config(){return o(b)},get strings(){return o(Ee)}});var he=J(G,2);{var dt=pt=>{jr(pt,{get logo(){return o(_t)},get strings(){return o(Ee)}})};oe(he,pt=>{o(xn)&&o(b).codeChallengeDisplay!=="standard"&&pt(dt)})}I(C,Oe)},$$slots:{default:!0}})}}),I(h,p)};oe(Sa,h=>{o(ge)||o(E)===B.EXPIRED||!c?h(Ta):o(se)&&o(E)===B.CODE&&h($a,1)})}ie(Je),yt(Je,h=>w(R,h),()=>o(R)),me(h=>{P(Je,"data-state",o(E)),P(Je,"data-display",o(b).display||void 0),P(Je,"data-placement",h),P(Je,"data-visible",o(Q)||void 0),P(Je,"dir",o(Qt)),P(vr,"for",o(Wt)),Je.dir=Je.dir},[()=>Jo(o(b).display)]),I(t,zi);var Ma=ht(pa);return s(),Ma}typeof window<"u"&&window.customElements&&!customElements.get("altcha-widget")&&customElements.define("altcha-widget",wt(Gc,{auto:{type:"String"},challenge:{type:"String"},configuration:{type:"String"},display:{type:"String"},form:{type:"String"},language:{type:"String"},name:{type:"String"},theme:{type:"String"},type:{type:"String"},workers:{type:"Number"}},[],["configure","getConfiguration","getState","hide","log","reset","setState","show","updateUI","verify"]));var $o=`(function() {
  "use strict";
  function assertAlgorithm(algorithm, allowed) {
    if (!allowed.includes(algorithm)) {
      throw new Error(
        \`Unsupported algorithm: \${String(algorithm)}. Expected one of: \${allowed.join(", ")}.\`
      );
    }
  }
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
    if (!/^[0-9a-fA-F]*$/.test(hex)) {
      throw new Error("Hex string contains non-hex characters.");
    }
    const buffer = new Uint8Array(hex.length / 2);
    for (let i = 0; i < buffer.length; i++) {
      buffer[i] = parseInt(hex.substring(i * 2, i * 2 + 2), 16);
    }
    return buffer;
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  var HmacAlgorithm = /* @__PURE__ */ ((HmacAlgorithm2) => {
    HmacAlgorithm2["SHA_256"] = "SHA-256";
    HmacAlgorithm2["SHA_384"] = "SHA-384";
    HmacAlgorithm2["SHA_512"] = "SHA-512";
    return HmacAlgorithm2;
  })(HmacAlgorithm || {});
  Object.values(HmacAlgorithm);
  const MAX_COUNTER = {
    string: Number.MAX_SAFE_INTEGER,
    uint32: 4294967295
  };
  function isValidCounter(n, mode) {
    return Number.isInteger(n) && n >= 0 && n <= MAX_COUNTER[mode];
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    nonce;
    mode;
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     * Throws a RangeError unless the counter is an integer the mode encodes exactly.
     */
    setCounter(n) {
      if (!isValidCounter(n, this.mode)) {
        throw new RangeError(
          \`counter must be an integer from 0 to \${MAX_COUNTER[this.mode]}. Got: \${n}\`
        );
      }
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  function assertKeyPrefix(keyPrefix, keyLength) {
    if (typeof keyPrefix !== "string" || !/^[0-9a-fA-F]+$/.test(keyPrefix)) {
      throw new Error("keyPrefix must be a non-empty hex string.");
    }
    if (keyPrefix.length > keyLength * 2) {
      throw new Error(
        \`keyPrefix (\${keyPrefix.length} hex characters) must not be longer than the key (keyLength: \${keyLength} bytes).\`
      );
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
    const { nonce, keyLength = 32, keyPrefix, salt } = challenge.parameters;
    assertKeyPrefix(keyPrefix, keyLength);
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixHex = keyPrefix.toLowerCase();
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
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefixHex)) {
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
    const { algorithm, cost, keyLength = 32 } = parameters;
    assertAlgorithm(algorithm, ["PBKDF2/SHA-256", "PBKDF2/SHA-384", "PBKDF2/SHA-512"]);
    const passwordKey = await crypto.subtle.importKey(
      "raw",
      password,
      { name: "PBKDF2" },
      false,
      ["deriveBits"]
    );
    const derivedBits = await crypto.subtle.deriveBits(
      {
        name: "PBKDF2",
        salt,
        iterations: cost,
        hash: algorithm.slice("PBKDF2/".length)
      },
      passwordKey,
      keyLength * 8
    );
    return {
      parameters: {},
      derivedKey: new Uint8Array(derivedBits)
    };
  }
  handler({
    deriveKey
  });
})();
`,gs=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",$o],{type:"text/javascript;charset=utf-8"});function hi(t){let e;try{if(e=gs&&(self.URL||self.webkitURL).createObjectURL(gs),!e)throw"";let n=new Worker(e,{name:t?.name});return n.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),n}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent($o),{name:t?.name})}}var Mo=`(function() {
  "use strict";
  function assertAlgorithm(algorithm, allowed) {
    if (!allowed.includes(algorithm)) {
      throw new Error(
        \`Unsupported algorithm: \${String(algorithm)}. Expected one of: \${allowed.join(", ")}.\`
      );
    }
  }
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
    if (!/^[0-9a-fA-F]*$/.test(hex)) {
      throw new Error("Hex string contains non-hex characters.");
    }
    const buffer = new Uint8Array(hex.length / 2);
    for (let i = 0; i < buffer.length; i++) {
      buffer[i] = parseInt(hex.substring(i * 2, i * 2 + 2), 16);
    }
    return buffer;
  }
  async function delay(ms) {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
  function timeDuration(start) {
    return Math.floor((performance.now() - start) * 10) / 10;
  }
  var HmacAlgorithm = /* @__PURE__ */ ((HmacAlgorithm2) => {
    HmacAlgorithm2["SHA_256"] = "SHA-256";
    HmacAlgorithm2["SHA_384"] = "SHA-384";
    HmacAlgorithm2["SHA_512"] = "SHA-512";
    return HmacAlgorithm2;
  })(HmacAlgorithm || {});
  Object.values(HmacAlgorithm);
  const MAX_COUNTER = {
    string: Number.MAX_SAFE_INTEGER,
    uint32: 4294967295
  };
  function isValidCounter(n, mode) {
    return Number.isInteger(n) && n >= 0 && n <= MAX_COUNTER[mode];
  }
  class PasswordBuffer {
    constructor(nonce, mode = "uint32") {
      this.nonce = nonce;
      this.mode = mode;
      this.buffer = new Uint8Array(this.nonce.length + this.COUNTER_BYTES);
      this.buffer.set(this.nonce, 0);
      this.dataView = new DataView(this.buffer.buffer);
    }
    nonce;
    mode;
    COUNTER_BYTES = 4;
    buffer;
    dataView;
    encoder = new TextEncoder();
    /**
     * Appends the counter to the nonce buffer.
     * In 'string' mode, encodes the counter as a UTF-8 string.
     * In 'uint32' mode, writes the counter as a big-endian 32-bit integer.
     * Throws a RangeError unless the counter is an integer the mode encodes exactly.
     */
    setCounter(n) {
      if (!isValidCounter(n, this.mode)) {
        throw new RangeError(
          \`counter must be an integer from 0 to \${MAX_COUNTER[this.mode]}. Got: \${n}\`
        );
      }
      if (this.mode === "string") {
        return concatBuffers(this.nonce, this.encoder.encode(n.toString()));
      }
      this.dataView.setUint32(this.nonce.length, n, false);
      return this.buffer;
    }
  }
  function assertKeyPrefix(keyPrefix, keyLength) {
    if (typeof keyPrefix !== "string" || !/^[0-9a-fA-F]+$/.test(keyPrefix)) {
      throw new Error("keyPrefix must be a non-empty hex string.");
    }
    if (keyPrefix.length > keyLength * 2) {
      throw new Error(
        \`keyPrefix (\${keyPrefix.length} hex characters) must not be longer than the key (keyLength: \${keyLength} bytes).\`
      );
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
    const { nonce, keyLength = 32, keyPrefix, salt } = challenge.parameters;
    assertKeyPrefix(keyPrefix, keyLength);
    const nonceBuf = hexToBuffer(nonce);
    const saltBuf = hexToBuffer(salt);
    const keyPrefixHex = keyPrefix.toLowerCase();
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
      if (keyPrefixBuf ? bufferStartsWith(derivedKey, keyPrefixBuf) : bufferToHex(derivedKey).startsWith(keyPrefixHex)) {
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
    assertAlgorithm(algorithm, ["SHA-256", "SHA-384", "SHA-512"]);
    const iterations = Math.max(1, parameters.cost);
    let derivedKey = concatBuffers(salt, password);
    for (let i = 0; i < iterations; i++) {
      derivedKey = new Uint8Array(await crypto.subtle.digest(algorithm, derivedKey));
    }
    return {
      parameters: {},
      derivedKey: derivedKey.slice(0, keyLength)
    };
  }
  handler({
    deriveKey
  });
})();
`,ms=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",Mo],{type:"text/javascript;charset=utf-8"});function fi(t){let e;try{if(e=ms&&(self.URL||self.webkitURL).createObjectURL(ms),!e)throw"";let n=new Worker(e,{name:t?.name});return n.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),n}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(Mo),{name:t?.name})}}var Wc=`:root {
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
@supports (hanging-punctuation: first) and (font: -apple-system-body) and (-webkit-appearance: none) {
  .altcha-checkbox input {
    /* Safari-only: fixes focus outline */
  }
  .altcha-checkbox input:focus {
    outline-width: 2px;
    outline-style: solid;
  }
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
}`;Bc(Wc);$altcha.algorithms.set("SHA-256",()=>new fi);$altcha.algorithms.set("SHA-384",()=>new fi);$altcha.algorithms.set("SHA-512",()=>new fi);$altcha.algorithms.set("PBKDF2/SHA-256",()=>new hi);$altcha.algorithms.set("PBKDF2/SHA-384",()=>new hi);$altcha.algorithms.set("PBKDF2/SHA-512",()=>new hi);var Xn=()=>{};function Jc(t,e){return t!=t?e==e:t!==e||t!==null&&typeof t=="object"||typeof t=="function"}function Xc(t,e,n){if(t==null)return e(void 0),Xn;let r=Qc(()=>t.subscribe(e,n));return r.unsubscribe?()=>r.unsubscribe():r}var Gt=[];function Zc(t,e=Xn){let n=null,r=new Set;function i(l){if(Jc(t,l)&&(t=l,n)){let u=!Gt.length;for(let c of r)c[1](),Gt.push(c,t);if(u){for(let c=0;c<Gt.length;c+=2)Gt[c][0](Gt[c+1]);Gt.length=0}}}function s(l){i(l(t))}function a(l,u=Xn){let c=[l,u];return r.add(c),r.size===1&&(n=e(i,s)||Xn),l(t),()=>{r.delete(c),r.size===0&&n&&(n(),n=null)}}return{set:i,update:s,subscribe:a}}function Jn(t){let e;return Xc(t,n=>e=n)(),e}var di=!1;function Qc(t){var e=di;try{return di=!0,t()}finally{di=e}}function No(t){let e={get:n=>Jn(e.store)[n],set:(n,r)=>{typeof n=="string"?Object.assign(Jn(e.store),{[n]:r}):Object.assign(Jn(e.store),n),e.store.set(Jn(e.store))},store:Zc(t)};return e}globalThis.$altcha=globalThis.$altcha||{algorithms:new Map,defaults:No({}),i18n:No({}),instances:new Set,plugins:new Set};var eu={ariaLinkLabel:"Altcha (site officiel)",enterCode:"Entrez le code",enterCodeAria:"Entrez le code que vous entendez. Appuyez sur Espace pour \xE9couter l'audio.",error:"\xC9chec de la v\xE9rification. Essayez \xE0 nouveau plus tard.",expired:"La v\xE9rification a expir\xE9. Essayez \xE0 nouveau.",footer:'Prot\xE9g\xE9 par <a href="https://altcha.org/" target="_blank" rel="noopener" aria-label="Altcha (site officiel, s\u2019ouvre dans un nouvel onglet)">ALTCHA</a>',getAudioChallenge:"Obtenir un d\xE9fi audio",label:"Je ne suis pas un robot",loading:"Chargement...",reload:"Recharger",verify:"V\xE9rifier",verificationRequired:"V\xE9rification requise !",verified:"V\xE9rifi\xE9",verifying:"V\xE9rification en cours...",waitAlert:"V\xE9rification en cours... veuillez patienter.",cancel:"Annuler",enterCodeFromImage:"Pour continuer, veuillez entrer le code de l'image ci-dessous."};globalThis.$altcha.i18n.set("fr-fr",eu);var pi=class{constructor(e,n,r){this.eventTarget=e,this.eventName=n,this.eventOptions=r,this.unorderedBindings=new Set}connect(){this.eventTarget.addEventListener(this.eventName,this,this.eventOptions)}disconnect(){this.eventTarget.removeEventListener(this.eventName,this,this.eventOptions)}bindingConnected(e){this.unorderedBindings.add(e)}bindingDisconnected(e){this.unorderedBindings.delete(e)}handleEvent(e){let n=tu(e);for(let r of this.bindings){if(n.immediatePropagationStopped)break;r.handleEvent(n)}}hasBindings(){return this.unorderedBindings.size>0}get bindings(){return Array.from(this.unorderedBindings).sort((e,n)=>{let r=e.index,i=n.index;return r<i?-1:r>i?1:0})}};function tu(t){if("immediatePropagationStopped"in t)return t;{let{stopImmediatePropagation:e}=t;return Object.assign(t,{immediatePropagationStopped:!1,stopImmediatePropagation(){this.immediatePropagationStopped=!0,e.call(this)}})}}var vi=class{constructor(e){this.application=e,this.eventListenerMaps=new Map,this.started=!1}start(){this.started||(this.started=!0,this.eventListeners.forEach(e=>e.connect()))}stop(){this.started&&(this.started=!1,this.eventListeners.forEach(e=>e.disconnect()))}get eventListeners(){return Array.from(this.eventListenerMaps.values()).reduce((e,n)=>e.concat(Array.from(n.values())),[])}bindingConnected(e){this.fetchEventListenerForBinding(e).bindingConnected(e)}bindingDisconnected(e,n=!1){this.fetchEventListenerForBinding(e).bindingDisconnected(e),n&&this.clearEventListenersForBinding(e)}handleError(e,n,r={}){this.application.handleError(e,`Error ${n}`,r)}clearEventListenersForBinding(e){let n=this.fetchEventListenerForBinding(e);n.hasBindings()||(n.disconnect(),this.removeMappedEventListenerFor(e))}removeMappedEventListenerFor(e){let{eventTarget:n,eventName:r,eventOptions:i}=e,s=this.fetchEventListenerMapForEventTarget(n),a=this.cacheKey(r,i);s.delete(a),s.size==0&&this.eventListenerMaps.delete(n)}fetchEventListenerForBinding(e){let{eventTarget:n,eventName:r,eventOptions:i}=e;return this.fetchEventListener(n,r,i)}fetchEventListener(e,n,r){let i=this.fetchEventListenerMapForEventTarget(e),s=this.cacheKey(n,r),a=i.get(s);return a||(a=this.createEventListener(e,n,r),i.set(s,a)),a}createEventListener(e,n,r){let i=new pi(e,n,r);return this.started&&i.connect(),i}fetchEventListenerMapForEventTarget(e){let n=this.eventListenerMaps.get(e);return n||(n=new Map,this.eventListenerMaps.set(e,n)),n}cacheKey(e,n){let r=[e];return Object.keys(n).sort().forEach(i=>{r.push(`${n[i]?"":"!"}${i}`)}),r.join(":")}},nu={stop({event:t,value:e}){return e&&t.stopPropagation(),!0},prevent({event:t,value:e}){return e&&t.preventDefault(),!0},self({event:t,value:e,element:n}){return e?n===t.target:!0}},ru=/^(?:(?:([^.]+?)\+)?(.+?)(?:\.(.+?))?(?:@(window|document))?->)?(.+?)(?:#([^:]+?))(?::(.+))?$/;function iu(t){let n=t.trim().match(ru)||[],r=n[2],i=n[3];return i&&!["keydown","keyup","keypress"].includes(r)&&(r+=`.${i}`,i=""),{eventTarget:su(n[4]),eventName:r,eventOptions:n[7]?ou(n[7]):{},identifier:n[5],methodName:n[6],keyFilter:n[1]||i}}function su(t){if(t=="window")return window;if(t=="document")return document}function ou(t){return t.split(":").reduce((e,n)=>Object.assign(e,{[n.replace(/^!/,"")]:!/^!/.test(n)}),{})}function au(t){if(t==window)return"window";if(t==document)return"document"}function Ri(t){return t.replace(/(?:[_-])([a-z0-9])/g,(e,n)=>n.toUpperCase())}function gi(t){return Ri(t.replace(/--/g,"-").replace(/__/g,"_"))}function kn(t){return t.charAt(0).toUpperCase()+t.slice(1)}function Uo(t){return t.replace(/([A-Z])/g,(e,n)=>`-${n.toLowerCase()}`)}function lu(t){return t.match(/[^\s]+/g)||[]}function Fo(t){return t!=null}function mi(t,e){return Object.prototype.hasOwnProperty.call(t,e)}var Lo=["meta","ctrl","alt","shift"],bi=class{constructor(e,n,r,i){this.element=e,this.index=n,this.eventTarget=r.eventTarget||e,this.eventName=r.eventName||cu(e)||Zn("missing event name"),this.eventOptions=r.eventOptions||{},this.identifier=r.identifier||Zn("missing identifier"),this.methodName=r.methodName||Zn("missing method name"),this.keyFilter=r.keyFilter||"",this.schema=i}static forToken(e,n){return new this(e.element,e.index,iu(e.content),n)}toString(){let e=this.keyFilter?`.${this.keyFilter}`:"",n=this.eventTargetName?`@${this.eventTargetName}`:"";return`${this.eventName}${e}${n}->${this.identifier}#${this.methodName}`}shouldIgnoreKeyboardEvent(e){if(!this.keyFilter)return!1;let n=this.keyFilter.split("+");if(this.keyFilterDissatisfied(e,n))return!0;let r=n.filter(i=>!Lo.includes(i))[0];return r?(mi(this.keyMappings,r)||Zn(`contains unknown key filter: ${this.keyFilter}`),this.keyMappings[r].toLowerCase()!==e.key.toLowerCase()):!1}shouldIgnoreMouseEvent(e){if(!this.keyFilter)return!1;let n=[this.keyFilter];return!!this.keyFilterDissatisfied(e,n)}get params(){let e={},n=new RegExp(`^data-${this.identifier}-(.+)-param$`,"i");for(let{name:r,value:i}of Array.from(this.element.attributes)){let s=r.match(n),a=s&&s[1];a&&(e[Ri(a)]=uu(i))}return e}get eventTargetName(){return au(this.eventTarget)}get keyMappings(){return this.schema.keyMappings}keyFilterDissatisfied(e,n){let[r,i,s,a]=Lo.map(l=>n.includes(l));return e.metaKey!==r||e.ctrlKey!==i||e.altKey!==s||e.shiftKey!==a}},Io={a:()=>"click",button:()=>"click",form:()=>"submit",details:()=>"toggle",input:t=>t.getAttribute("type")=="submit"?"click":"input",select:()=>"change",textarea:()=>"input"};function cu(t){let e=t.tagName.toLowerCase();if(e in Io)return Io[e](t)}function Zn(t){throw new Error(t)}function uu(t){try{return JSON.parse(t)}catch{return t}}var yi=class{constructor(e,n){this.context=e,this.action=n}get index(){return this.action.index}get eventTarget(){return this.action.eventTarget}get eventOptions(){return this.action.eventOptions}get identifier(){return this.context.identifier}handleEvent(e){let n=this.prepareActionEvent(e);this.willBeInvokedByEvent(e)&&this.applyEventModifiers(n)&&this.invokeWithEvent(n)}get eventName(){return this.action.eventName}get method(){let e=this.controller[this.methodName];if(typeof e=="function")return e;throw new Error(`Action "${this.action}" references undefined method "${this.methodName}"`)}applyEventModifiers(e){let{element:n}=this.action,{actionDescriptorFilters:r}=this.context.application,{controller:i}=this.context,s=!0;for(let[a,l]of Object.entries(this.eventOptions))if(a in r){let u=r[a];s=s&&u({name:a,value:l,event:e,element:n,controller:i})}else continue;return s}prepareActionEvent(e){return Object.assign(e,{params:this.action.params})}invokeWithEvent(e){let{target:n,currentTarget:r}=e;try{this.method.call(this.controller,e),this.context.logDebugActivity(this.methodName,{event:e,target:n,currentTarget:r,action:this.methodName})}catch(i){let{identifier:s,controller:a,element:l,index:u}=this,c={identifier:s,controller:a,element:l,index:u,event:e};this.context.handleError(i,`invoking action "${this.action}"`,c)}}willBeInvokedByEvent(e){let n=e.target;return e instanceof KeyboardEvent&&this.action.shouldIgnoreKeyboardEvent(e)||e instanceof MouseEvent&&this.action.shouldIgnoreMouseEvent(e)?!1:this.element===n?!0:n instanceof Element&&this.element.contains(n)?this.scope.containsElement(n):this.scope.containsElement(this.action.element)}get controller(){return this.context.controller}get methodName(){return this.action.methodName}get element(){return this.scope.element}get scope(){return this.context.scope}},Qn=class{constructor(e,n){this.mutationObserverInit={attributes:!0,childList:!0,subtree:!0},this.element=e,this.started=!1,this.delegate=n,this.elements=new Set,this.mutationObserver=new MutationObserver(r=>this.processMutations(r))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,this.mutationObserverInit),this.refresh())}pause(e){this.started&&(this.mutationObserver.disconnect(),this.started=!1),e(),this.started||(this.mutationObserver.observe(this.element,this.mutationObserverInit),this.started=!0)}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started){let e=new Set(this.matchElementsInTree());for(let n of Array.from(this.elements))e.has(n)||this.removeElement(n);for(let n of Array.from(e))this.addElement(n)}}processMutations(e){if(this.started)for(let n of e)this.processMutation(n)}processMutation(e){e.type=="attributes"?this.processAttributeChange(e.target,e.attributeName):e.type=="childList"&&(this.processRemovedNodes(e.removedNodes),this.processAddedNodes(e.addedNodes))}processAttributeChange(e,n){this.elements.has(e)?this.delegate.elementAttributeChanged&&this.matchElement(e)?this.delegate.elementAttributeChanged(e,n):this.removeElement(e):this.matchElement(e)&&this.addElement(e)}processRemovedNodes(e){for(let n of Array.from(e)){let r=this.elementFromNode(n);r&&this.processTree(r,this.removeElement)}}processAddedNodes(e){for(let n of Array.from(e)){let r=this.elementFromNode(n);r&&this.elementIsActive(r)&&this.processTree(r,this.addElement)}}matchElement(e){return this.delegate.matchElement(e)}matchElementsInTree(e=this.element){return this.delegate.matchElementsInTree(e)}processTree(e,n){for(let r of this.matchElementsInTree(e))n.call(this,r)}elementFromNode(e){if(e.nodeType==Node.ELEMENT_NODE)return e}elementIsActive(e){return e.isConnected!=this.element.isConnected?!1:this.element.contains(e)}addElement(e){this.elements.has(e)||this.elementIsActive(e)&&(this.elements.add(e),this.delegate.elementMatched&&this.delegate.elementMatched(e))}removeElement(e){this.elements.has(e)&&(this.elements.delete(e),this.delegate.elementUnmatched&&this.delegate.elementUnmatched(e))}},er=class{constructor(e,n,r){this.attributeName=n,this.delegate=r,this.elementObserver=new Qn(e,this)}get element(){return this.elementObserver.element}get selector(){return`[${this.attributeName}]`}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get started(){return this.elementObserver.started}matchElement(e){return e.hasAttribute(this.attributeName)}matchElementsInTree(e){let n=this.matchElement(e)?[e]:[],r=Array.from(e.querySelectorAll(this.selector));return n.concat(r)}elementMatched(e){this.delegate.elementMatchedAttribute&&this.delegate.elementMatchedAttribute(e,this.attributeName)}elementUnmatched(e){this.delegate.elementUnmatchedAttribute&&this.delegate.elementUnmatchedAttribute(e,this.attributeName)}elementAttributeChanged(e,n){this.delegate.elementAttributeValueChanged&&this.attributeName==n&&this.delegate.elementAttributeValueChanged(e,n)}};function hu(t,e,n){Ko(t,e).add(n)}function fu(t,e,n){Ko(t,e).delete(n),du(t,e)}function Ko(t,e){let n=t.get(e);return n||(n=new Set,t.set(e,n)),n}function du(t,e){let n=t.get(e);n!=null&&n.size==0&&t.delete(e)}var ft=class{constructor(){this.valuesByKey=new Map}get keys(){return Array.from(this.valuesByKey.keys())}get values(){return Array.from(this.valuesByKey.values()).reduce((n,r)=>n.concat(Array.from(r)),[])}get size(){return Array.from(this.valuesByKey.values()).reduce((n,r)=>n+r.size,0)}add(e,n){hu(this.valuesByKey,e,n)}delete(e,n){fu(this.valuesByKey,e,n)}has(e,n){let r=this.valuesByKey.get(e);return r!=null&&r.has(n)}hasKey(e){return this.valuesByKey.has(e)}hasValue(e){return Array.from(this.valuesByKey.values()).some(r=>r.has(e))}getValuesForKey(e){let n=this.valuesByKey.get(e);return n?Array.from(n):[]}getKeysForValue(e){return Array.from(this.valuesByKey).filter(([n,r])=>r.has(e)).map(([n,r])=>n)}};var wi=class{constructor(e,n,r,i){this._selector=n,this.details=i,this.elementObserver=new Qn(e,this),this.delegate=r,this.matchesByElement=new ft}get started(){return this.elementObserver.started}get selector(){return this._selector}set selector(e){this._selector=e,this.refresh()}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get element(){return this.elementObserver.element}matchElement(e){let{selector:n}=this;if(n){let r=e.matches(n);return this.delegate.selectorMatchElement?r&&this.delegate.selectorMatchElement(e,this.details):r}else return!1}matchElementsInTree(e){let{selector:n}=this;if(n){let r=this.matchElement(e)?[e]:[],i=Array.from(e.querySelectorAll(n)).filter(s=>this.matchElement(s));return r.concat(i)}else return[]}elementMatched(e){let{selector:n}=this;n&&this.selectorMatched(e,n)}elementUnmatched(e){let n=this.matchesByElement.getKeysForValue(e);for(let r of n)this.selectorUnmatched(e,r)}elementAttributeChanged(e,n){let{selector:r}=this;if(r){let i=this.matchElement(e),s=this.matchesByElement.has(r,e);i&&!s?this.selectorMatched(e,r):!i&&s&&this.selectorUnmatched(e,r)}}selectorMatched(e,n){this.delegate.selectorMatched(e,n,this.details),this.matchesByElement.add(n,e)}selectorUnmatched(e,n){this.delegate.selectorUnmatched(e,n,this.details),this.matchesByElement.delete(n,e)}},_i=class{constructor(e,n){this.element=e,this.delegate=n,this.started=!1,this.stringMap=new Map,this.mutationObserver=new MutationObserver(r=>this.processMutations(r))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,{attributes:!0,attributeOldValue:!0}),this.refresh())}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started)for(let e of this.knownAttributeNames)this.refreshAttribute(e,null)}processMutations(e){if(this.started)for(let n of e)this.processMutation(n)}processMutation(e){let n=e.attributeName;n&&this.refreshAttribute(n,e.oldValue)}refreshAttribute(e,n){let r=this.delegate.getStringMapKeyForAttribute(e);if(r!=null){this.stringMap.has(e)||this.stringMapKeyAdded(r,e);let i=this.element.getAttribute(e);if(this.stringMap.get(e)!=i&&this.stringMapValueChanged(i,r,n),i==null){let s=this.stringMap.get(e);this.stringMap.delete(e),s&&this.stringMapKeyRemoved(r,e,s)}else this.stringMap.set(e,i)}}stringMapKeyAdded(e,n){this.delegate.stringMapKeyAdded&&this.delegate.stringMapKeyAdded(e,n)}stringMapValueChanged(e,n,r){this.delegate.stringMapValueChanged&&this.delegate.stringMapValueChanged(e,n,r)}stringMapKeyRemoved(e,n,r){this.delegate.stringMapKeyRemoved&&this.delegate.stringMapKeyRemoved(e,n,r)}get knownAttributeNames(){return Array.from(new Set(this.currentAttributeNames.concat(this.recordedAttributeNames)))}get currentAttributeNames(){return Array.from(this.element.attributes).map(e=>e.name)}get recordedAttributeNames(){return Array.from(this.stringMap.keys())}},tr=class{constructor(e,n,r){this.attributeObserver=new er(e,n,this),this.delegate=r,this.tokensByElement=new ft}get started(){return this.attributeObserver.started}start(){this.attributeObserver.start()}pause(e){this.attributeObserver.pause(e)}stop(){this.attributeObserver.stop()}refresh(){this.attributeObserver.refresh()}get element(){return this.attributeObserver.element}get attributeName(){return this.attributeObserver.attributeName}elementMatchedAttribute(e){this.tokensMatched(this.readTokensForElement(e))}elementAttributeValueChanged(e){let[n,r]=this.refreshTokensForElement(e);this.tokensUnmatched(n),this.tokensMatched(r)}elementUnmatchedAttribute(e){this.tokensUnmatched(this.tokensByElement.getValuesForKey(e))}tokensMatched(e){e.forEach(n=>this.tokenMatched(n))}tokensUnmatched(e){e.forEach(n=>this.tokenUnmatched(n))}tokenMatched(e){this.delegate.tokenMatched(e),this.tokensByElement.add(e.element,e)}tokenUnmatched(e){this.delegate.tokenUnmatched(e),this.tokensByElement.delete(e.element,e)}refreshTokensForElement(e){let n=this.tokensByElement.getValuesForKey(e),r=this.readTokensForElement(e),i=vu(n,r).findIndex(([s,a])=>!gu(s,a));return i==-1?[[],[]]:[n.slice(i),r.slice(i)]}readTokensForElement(e){let n=this.attributeName,r=e.getAttribute(n)||"";return pu(r,e,n)}};function pu(t,e,n){return t.trim().split(/\s+/).filter(r=>r.length).map((r,i)=>({element:e,attributeName:n,content:r,index:i}))}function vu(t,e){let n=Math.max(t.length,e.length);return Array.from({length:n},(r,i)=>[t[i],e[i]])}function gu(t,e){return t&&e&&t.index==e.index&&t.content==e.content}var nr=class{constructor(e,n,r){this.tokenListObserver=new tr(e,n,this),this.delegate=r,this.parseResultsByToken=new WeakMap,this.valuesByTokenByElement=new WeakMap}get started(){return this.tokenListObserver.started}start(){this.tokenListObserver.start()}stop(){this.tokenListObserver.stop()}refresh(){this.tokenListObserver.refresh()}get element(){return this.tokenListObserver.element}get attributeName(){return this.tokenListObserver.attributeName}tokenMatched(e){let{element:n}=e,{value:r}=this.fetchParseResultForToken(e);r&&(this.fetchValuesByTokenForElement(n).set(e,r),this.delegate.elementMatchedValue(n,r))}tokenUnmatched(e){let{element:n}=e,{value:r}=this.fetchParseResultForToken(e);r&&(this.fetchValuesByTokenForElement(n).delete(e),this.delegate.elementUnmatchedValue(n,r))}fetchParseResultForToken(e){let n=this.parseResultsByToken.get(e);return n||(n=this.parseToken(e),this.parseResultsByToken.set(e,n)),n}fetchValuesByTokenForElement(e){let n=this.valuesByTokenByElement.get(e);return n||(n=new Map,this.valuesByTokenByElement.set(e,n)),n}parseToken(e){try{return{value:this.delegate.parseValueForToken(e)}}catch(n){return{error:n}}}},Ei=class{constructor(e,n){this.context=e,this.delegate=n,this.bindingsByAction=new Map}start(){this.valueListObserver||(this.valueListObserver=new nr(this.element,this.actionAttribute,this),this.valueListObserver.start())}stop(){this.valueListObserver&&(this.valueListObserver.stop(),delete this.valueListObserver,this.disconnectAllActions())}get element(){return this.context.element}get identifier(){return this.context.identifier}get actionAttribute(){return this.schema.actionAttribute}get schema(){return this.context.schema}get bindings(){return Array.from(this.bindingsByAction.values())}connectAction(e){let n=new yi(this.context,e);this.bindingsByAction.set(e,n),this.delegate.bindingConnected(n)}disconnectAction(e){let n=this.bindingsByAction.get(e);n&&(this.bindingsByAction.delete(e),this.delegate.bindingDisconnected(n))}disconnectAllActions(){this.bindings.forEach(e=>this.delegate.bindingDisconnected(e,!0)),this.bindingsByAction.clear()}parseValueForToken(e){let n=bi.forToken(e,this.schema);if(n.identifier==this.identifier)return n}elementMatchedValue(e,n){this.connectAction(n)}elementUnmatchedValue(e,n){this.disconnectAction(n)}},ki=class{constructor(e,n){this.context=e,this.receiver=n,this.stringMapObserver=new _i(this.element,this),this.valueDescriptorMap=this.controller.valueDescriptorMap}start(){this.stringMapObserver.start(),this.invokeChangedCallbacksForDefaultValues()}stop(){this.stringMapObserver.stop()}get element(){return this.context.element}get controller(){return this.context.controller}getStringMapKeyForAttribute(e){if(e in this.valueDescriptorMap)return this.valueDescriptorMap[e].name}stringMapKeyAdded(e,n){let r=this.valueDescriptorMap[n];this.hasValue(e)||this.invokeChangedCallback(e,r.writer(this.receiver[e]),r.writer(r.defaultValue))}stringMapValueChanged(e,n,r){let i=this.valueDescriptorNameMap[n];e!==null&&(r===null&&(r=i.writer(i.defaultValue)),this.invokeChangedCallback(n,e,r))}stringMapKeyRemoved(e,n,r){let i=this.valueDescriptorNameMap[e];this.hasValue(e)?this.invokeChangedCallback(e,i.writer(this.receiver[e]),r):this.invokeChangedCallback(e,i.writer(i.defaultValue),r)}invokeChangedCallbacksForDefaultValues(){for(let{key:e,name:n,defaultValue:r,writer:i}of this.valueDescriptors)r!=null&&!this.controller.data.has(e)&&this.invokeChangedCallback(n,i(r),void 0)}invokeChangedCallback(e,n,r){let i=`${e}Changed`,s=this.receiver[i];if(typeof s=="function"){let a=this.valueDescriptorNameMap[e];try{let l=a.reader(n),u=r;r&&(u=a.reader(r)),s.call(this.receiver,l,u)}catch(l){throw l instanceof TypeError&&(l.message=`Stimulus Value "${this.context.identifier}.${a.name}" - ${l.message}`),l}}}get valueDescriptors(){let{valueDescriptorMap:e}=this;return Object.keys(e).map(n=>e[n])}get valueDescriptorNameMap(){let e={};return Object.keys(this.valueDescriptorMap).forEach(n=>{let r=this.valueDescriptorMap[n];e[r.name]=r}),e}hasValue(e){let n=this.valueDescriptorNameMap[e],r=`has${kn(n.name)}`;return this.receiver[r]}},Ai=class{constructor(e,n){this.context=e,this.delegate=n,this.targetsByName=new ft}start(){this.tokenListObserver||(this.tokenListObserver=new tr(this.element,this.attributeName,this),this.tokenListObserver.start())}stop(){this.tokenListObserver&&(this.disconnectAllTargets(),this.tokenListObserver.stop(),delete this.tokenListObserver)}tokenMatched({element:e,content:n}){this.scope.containsElement(e)&&this.connectTarget(e,n)}tokenUnmatched({element:e,content:n}){this.disconnectTarget(e,n)}connectTarget(e,n){var r;this.targetsByName.has(n,e)||(this.targetsByName.add(n,e),(r=this.tokenListObserver)===null||r===void 0||r.pause(()=>this.delegate.targetConnected(e,n)))}disconnectTarget(e,n){var r;this.targetsByName.has(n,e)&&(this.targetsByName.delete(n,e),(r=this.tokenListObserver)===null||r===void 0||r.pause(()=>this.delegate.targetDisconnected(e,n)))}disconnectAllTargets(){for(let e of this.targetsByName.keys)for(let n of this.targetsByName.getValuesForKey(e))this.disconnectTarget(n,e)}get attributeName(){return`data-${this.context.identifier}-target`}get element(){return this.context.element}get scope(){return this.context.scope}};function An(t,e){let n=Ho(t);return Array.from(n.reduce((r,i)=>(bu(i,e).forEach(s=>r.add(s)),r),new Set))}function mu(t,e){return Ho(t).reduce((r,i)=>(r.push(...yu(i,e)),r),[])}function Ho(t){let e=[];for(;t;)e.push(t),t=Object.getPrototypeOf(t);return e.reverse()}function bu(t,e){let n=t[e];return Array.isArray(n)?n:[]}function yu(t,e){let n=t[e];return n?Object.keys(n).map(r=>[r,n[r]]):[]}var xi=class{constructor(e,n){this.started=!1,this.context=e,this.delegate=n,this.outletsByName=new ft,this.outletElementsByName=new ft,this.selectorObserverMap=new Map,this.attributeObserverMap=new Map}start(){this.started||(this.outletDefinitions.forEach(e=>{this.setupSelectorObserverForOutlet(e),this.setupAttributeObserverForOutlet(e)}),this.started=!0,this.dependentContexts.forEach(e=>e.refresh()))}refresh(){this.selectorObserverMap.forEach(e=>e.refresh()),this.attributeObserverMap.forEach(e=>e.refresh())}stop(){this.started&&(this.started=!1,this.disconnectAllOutlets(),this.stopSelectorObservers(),this.stopAttributeObservers())}stopSelectorObservers(){this.selectorObserverMap.size>0&&(this.selectorObserverMap.forEach(e=>e.stop()),this.selectorObserverMap.clear())}stopAttributeObservers(){this.attributeObserverMap.size>0&&(this.attributeObserverMap.forEach(e=>e.stop()),this.attributeObserverMap.clear())}selectorMatched(e,n,{outletName:r}){let i=this.getOutlet(e,r);i&&this.connectOutlet(i,e,r)}selectorUnmatched(e,n,{outletName:r}){let i=this.getOutletFromMap(e,r);i&&this.disconnectOutlet(i,e,r)}selectorMatchElement(e,{outletName:n}){let r=this.selector(n),i=this.hasOutlet(e,n),s=e.matches(`[${this.schema.controllerAttribute}~=${n}]`);return r?i&&s&&e.matches(r):!1}elementMatchedAttribute(e,n){let r=this.getOutletNameFromOutletAttributeName(n);r&&this.updateSelectorObserverForOutlet(r)}elementAttributeValueChanged(e,n){let r=this.getOutletNameFromOutletAttributeName(n);r&&this.updateSelectorObserverForOutlet(r)}elementUnmatchedAttribute(e,n){let r=this.getOutletNameFromOutletAttributeName(n);r&&this.updateSelectorObserverForOutlet(r)}connectOutlet(e,n,r){var i;this.outletElementsByName.has(r,n)||(this.outletsByName.add(r,e),this.outletElementsByName.add(r,n),(i=this.selectorObserverMap.get(r))===null||i===void 0||i.pause(()=>this.delegate.outletConnected(e,n,r)))}disconnectOutlet(e,n,r){var i;this.outletElementsByName.has(r,n)&&(this.outletsByName.delete(r,e),this.outletElementsByName.delete(r,n),(i=this.selectorObserverMap.get(r))===null||i===void 0||i.pause(()=>this.delegate.outletDisconnected(e,n,r)))}disconnectAllOutlets(){for(let e of this.outletElementsByName.keys)for(let n of this.outletElementsByName.getValuesForKey(e))for(let r of this.outletsByName.getValuesForKey(e))this.disconnectOutlet(r,n,e)}updateSelectorObserverForOutlet(e){let n=this.selectorObserverMap.get(e);n&&(n.selector=this.selector(e))}setupSelectorObserverForOutlet(e){let n=this.selector(e),r=new wi(document.body,n,this,{outletName:e});this.selectorObserverMap.set(e,r),r.start()}setupAttributeObserverForOutlet(e){let n=this.attributeNameForOutletName(e),r=new er(this.scope.element,n,this);this.attributeObserverMap.set(e,r),r.start()}selector(e){return this.scope.outlets.getSelectorForOutletName(e)}attributeNameForOutletName(e){return this.scope.schema.outletAttributeForScope(this.identifier,e)}getOutletNameFromOutletAttributeName(e){return this.outletDefinitions.find(n=>this.attributeNameForOutletName(n)===e)}get outletDependencies(){let e=new ft;return this.router.modules.forEach(n=>{let r=n.definition.controllerConstructor;An(r,"outlets").forEach(s=>e.add(s,n.identifier))}),e}get outletDefinitions(){return this.outletDependencies.getKeysForValue(this.identifier)}get dependentControllerIdentifiers(){return this.outletDependencies.getValuesForKey(this.identifier)}get dependentContexts(){let e=this.dependentControllerIdentifiers;return this.router.contexts.filter(n=>e.includes(n.identifier))}hasOutlet(e,n){return!!this.getOutlet(e,n)||!!this.getOutletFromMap(e,n)}getOutlet(e,n){return this.application.getControllerForElementAndIdentifier(e,n)}getOutletFromMap(e,n){return this.outletsByName.getValuesForKey(n).find(r=>r.element===e)}get scope(){return this.context.scope}get schema(){return this.context.schema}get identifier(){return this.context.identifier}get application(){return this.context.application}get router(){return this.application.router}},Ci=class{constructor(e,n){this.logDebugActivity=(r,i={})=>{let{identifier:s,controller:a,element:l}=this;i=Object.assign({identifier:s,controller:a,element:l},i),this.application.logDebugActivity(this.identifier,r,i)},this.module=e,this.scope=n,this.controller=new e.controllerConstructor(this),this.bindingObserver=new Ei(this,this.dispatcher),this.valueObserver=new ki(this,this.controller),this.targetObserver=new Ai(this,this),this.outletObserver=new xi(this,this);try{this.controller.initialize(),this.logDebugActivity("initialize")}catch(r){this.handleError(r,"initializing controller")}}connect(){this.bindingObserver.start(),this.valueObserver.start(),this.targetObserver.start(),this.outletObserver.start();try{this.controller.connect(),this.logDebugActivity("connect")}catch(e){this.handleError(e,"connecting controller")}}refresh(){this.outletObserver.refresh()}disconnect(){try{this.controller.disconnect(),this.logDebugActivity("disconnect")}catch(e){this.handleError(e,"disconnecting controller")}this.outletObserver.stop(),this.targetObserver.stop(),this.valueObserver.stop(),this.bindingObserver.stop()}get application(){return this.module.application}get identifier(){return this.module.identifier}get schema(){return this.application.schema}get dispatcher(){return this.application.dispatcher}get element(){return this.scope.element}get parentElement(){return this.element.parentElement}handleError(e,n,r={}){let{identifier:i,controller:s,element:a}=this;r=Object.assign({identifier:i,controller:s,element:a},r),this.application.handleError(e,`Error ${n}`,r)}targetConnected(e,n){this.invokeControllerMethod(`${n}TargetConnected`,e)}targetDisconnected(e,n){this.invokeControllerMethod(`${n}TargetDisconnected`,e)}outletConnected(e,n,r){this.invokeControllerMethod(`${gi(r)}OutletConnected`,e,n)}outletDisconnected(e,n,r){this.invokeControllerMethod(`${gi(r)}OutletDisconnected`,e,n)}invokeControllerMethod(e,...n){let r=this.controller;typeof r[e]=="function"&&r[e](...n)}};function wu(t){return _u(t,Eu(t))}function _u(t,e){let n=Cu(t),r=ku(t.prototype,e);return Object.defineProperties(n.prototype,r),n}function Eu(t){return An(t,"blessings").reduce((n,r)=>{let i=r(t);for(let s in i){let a=n[s]||{};n[s]=Object.assign(a,i[s])}return n},{})}function ku(t,e){return xu(e).reduce((n,r)=>{let i=Au(t,e,r);return i&&Object.assign(n,{[r]:i}),n},{})}function Au(t,e,n){let r=Object.getOwnPropertyDescriptor(t,n);if(!(r&&"value"in r)){let s=Object.getOwnPropertyDescriptor(e,n).value;return r&&(s.get=r.get||s.get,s.set=r.set||s.set),s}}var xu=typeof Object.getOwnPropertySymbols=="function"?t=>[...Object.getOwnPropertyNames(t),...Object.getOwnPropertySymbols(t)]:Object.getOwnPropertyNames,Cu=(()=>{function t(n){function r(){return Reflect.construct(n,arguments,new.target)}return r.prototype=Object.create(n.prototype,{constructor:{value:r}}),Reflect.setPrototypeOf(r,n),r}function e(){let r=t(function(){this.a.call(this)});return r.prototype.a=function(){},new r}try{return e(),t}catch{return r=>class extends r{}}})();function Ou(t){return{identifier:t.identifier,controllerConstructor:wu(t.controllerConstructor)}}var Oi=class{constructor(e,n){this.application=e,this.definition=Ou(n),this.contextsByScope=new WeakMap,this.connectedContexts=new Set}get identifier(){return this.definition.identifier}get controllerConstructor(){return this.definition.controllerConstructor}get contexts(){return Array.from(this.connectedContexts)}connectContextForScope(e){let n=this.fetchContextForScope(e);this.connectedContexts.add(n),n.connect()}disconnectContextForScope(e){let n=this.contextsByScope.get(e);n&&(this.connectedContexts.delete(n),n.disconnect())}fetchContextForScope(e){let n=this.contextsByScope.get(e);return n||(n=new Ci(this,e),this.contextsByScope.set(e,n)),n}},Si=class{constructor(e){this.scope=e}has(e){return this.data.has(this.getDataKey(e))}get(e){return this.getAll(e)[0]}getAll(e){let n=this.data.get(this.getDataKey(e))||"";return lu(n)}getAttributeName(e){return this.data.getAttributeNameForKey(this.getDataKey(e))}getDataKey(e){return`${e}-class`}get data(){return this.scope.data}},Ti=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get(e){let n=this.getAttributeNameForKey(e);return this.element.getAttribute(n)}set(e,n){let r=this.getAttributeNameForKey(e);return this.element.setAttribute(r,n),this.get(e)}has(e){let n=this.getAttributeNameForKey(e);return this.element.hasAttribute(n)}delete(e){if(this.has(e)){let n=this.getAttributeNameForKey(e);return this.element.removeAttribute(n),!0}else return!1}getAttributeNameForKey(e){return`data-${this.identifier}-${Uo(e)}`}},$i=class{constructor(e){this.warnedKeysByObject=new WeakMap,this.logger=e}warn(e,n,r){let i=this.warnedKeysByObject.get(e);i||(i=new Set,this.warnedKeysByObject.set(e,i)),i.has(n)||(i.add(n),this.logger.warn(r,e))}};function Mi(t,e){return`[${t}~="${e}"]`}var Ni=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((n,r)=>n||this.findTarget(r)||this.findLegacyTarget(r),void 0)}findAll(...e){return e.reduce((n,r)=>[...n,...this.findAllTargets(r),...this.findAllLegacyTargets(r)],[])}findTarget(e){let n=this.getSelectorForTargetName(e);return this.scope.findElement(n)}findAllTargets(e){let n=this.getSelectorForTargetName(e);return this.scope.findAllElements(n)}getSelectorForTargetName(e){let n=this.schema.targetAttributeForScope(this.identifier);return Mi(n,e)}findLegacyTarget(e){let n=this.getLegacySelectorForTargetName(e);return this.deprecate(this.scope.findElement(n),e)}findAllLegacyTargets(e){let n=this.getLegacySelectorForTargetName(e);return this.scope.findAllElements(n).map(r=>this.deprecate(r,e))}getLegacySelectorForTargetName(e){let n=`${this.identifier}.${e}`;return Mi(this.schema.targetAttribute,n)}deprecate(e,n){if(e){let{identifier:r}=this,i=this.schema.targetAttribute,s=this.schema.targetAttributeForScope(r);this.guide.warn(e,`target:${n}`,`Please replace ${i}="${r}.${n}" with ${s}="${n}". The ${i} attribute is deprecated and will be removed in a future version of Stimulus.`)}return e}get guide(){return this.scope.guide}},Fi=class{constructor(e,n){this.scope=e,this.controllerElement=n}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((n,r)=>n||this.findOutlet(r),void 0)}findAll(...e){return e.reduce((n,r)=>[...n,...this.findAllOutlets(r)],[])}getSelectorForOutletName(e){let n=this.schema.outletAttributeForScope(this.identifier,e);return this.controllerElement.getAttribute(n)}findOutlet(e){let n=this.getSelectorForOutletName(e);if(n)return this.findElement(n,e)}findAllOutlets(e){let n=this.getSelectorForOutletName(e);return n?this.findAllElements(n,e):[]}findElement(e,n){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,n))[0]}findAllElements(e,n){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,n))}matchesElement(e,n,r){let i=e.getAttribute(this.scope.schema.controllerAttribute)||"";return e.matches(n)&&i.split(" ").includes(r)}},Li=class t{constructor(e,n,r,i){this.targets=new Ni(this),this.classes=new Si(this),this.data=new Ti(this),this.containsElement=s=>s.closest(this.controllerSelector)===this.element,this.schema=e,this.element=n,this.identifier=r,this.guide=new $i(i),this.outlets=new Fi(this.documentScope,n)}findElement(e){return this.element.matches(e)?this.element:this.queryElements(e).find(this.containsElement)}findAllElements(e){return[...this.element.matches(e)?[this.element]:[],...this.queryElements(e).filter(this.containsElement)]}queryElements(e){return Array.from(this.element.querySelectorAll(e))}get controllerSelector(){return Mi(this.schema.controllerAttribute,this.identifier)}get isDocumentScope(){return this.element===document.documentElement}get documentScope(){return this.isDocumentScope?this:new t(this.schema,document.documentElement,this.identifier,this.guide.logger)}},Ii=class{constructor(e,n,r){this.element=e,this.schema=n,this.delegate=r,this.valueListObserver=new nr(this.element,this.controllerAttribute,this),this.scopesByIdentifierByElement=new WeakMap,this.scopeReferenceCounts=new WeakMap}start(){this.valueListObserver.start()}stop(){this.valueListObserver.stop()}get controllerAttribute(){return this.schema.controllerAttribute}parseValueForToken(e){let{element:n,content:r}=e;return this.parseValueForElementAndIdentifier(n,r)}parseValueForElementAndIdentifier(e,n){let r=this.fetchScopesByIdentifierForElement(e),i=r.get(n);return i||(i=this.delegate.createScopeForElementAndIdentifier(e,n),r.set(n,i)),i}elementMatchedValue(e,n){let r=(this.scopeReferenceCounts.get(n)||0)+1;this.scopeReferenceCounts.set(n,r),r==1&&this.delegate.scopeConnected(n)}elementUnmatchedValue(e,n){let r=this.scopeReferenceCounts.get(n);r&&(this.scopeReferenceCounts.set(n,r-1),r==1&&this.delegate.scopeDisconnected(n))}fetchScopesByIdentifierForElement(e){let n=this.scopesByIdentifierByElement.get(e);return n||(n=new Map,this.scopesByIdentifierByElement.set(e,n)),n}},Di=class{constructor(e){this.application=e,this.scopeObserver=new Ii(this.element,this.schema,this),this.scopesByIdentifier=new ft,this.modulesByIdentifier=new Map}get element(){return this.application.element}get schema(){return this.application.schema}get logger(){return this.application.logger}get controllerAttribute(){return this.schema.controllerAttribute}get modules(){return Array.from(this.modulesByIdentifier.values())}get contexts(){return this.modules.reduce((e,n)=>e.concat(n.contexts),[])}start(){this.scopeObserver.start()}stop(){this.scopeObserver.stop()}loadDefinition(e){this.unloadIdentifier(e.identifier);let n=new Oi(this.application,e);this.connectModule(n);let r=e.controllerConstructor.afterLoad;r&&r.call(e.controllerConstructor,e.identifier,this.application)}unloadIdentifier(e){let n=this.modulesByIdentifier.get(e);n&&this.disconnectModule(n)}getContextForElementAndIdentifier(e,n){let r=this.modulesByIdentifier.get(n);if(r)return r.contexts.find(i=>i.element==e)}proposeToConnectScopeForElementAndIdentifier(e,n){let r=this.scopeObserver.parseValueForElementAndIdentifier(e,n);r?this.scopeObserver.elementMatchedValue(r.element,r):console.error(`Couldn't find or create scope for identifier: "${n}" and element:`,e)}handleError(e,n,r){this.application.handleError(e,n,r)}createScopeForElementAndIdentifier(e,n){return new Li(this.schema,e,n,this.logger)}scopeConnected(e){this.scopesByIdentifier.add(e.identifier,e);let n=this.modulesByIdentifier.get(e.identifier);n&&n.connectContextForScope(e)}scopeDisconnected(e){this.scopesByIdentifier.delete(e.identifier,e);let n=this.modulesByIdentifier.get(e.identifier);n&&n.disconnectContextForScope(e)}connectModule(e){this.modulesByIdentifier.set(e.identifier,e),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(r=>e.connectContextForScope(r))}disconnectModule(e){this.modulesByIdentifier.delete(e.identifier),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(r=>e.disconnectContextForScope(r))}},Su={controllerAttribute:"data-controller",actionAttribute:"data-action",targetAttribute:"data-target",targetAttributeForScope:t=>`data-${t}-target`,outletAttributeForScope:(t,e)=>`data-${t}-${e}-outlet`,keyMappings:Object.assign(Object.assign({enter:"Enter",tab:"Tab",esc:"Escape",space:" ",up:"ArrowUp",down:"ArrowDown",left:"ArrowLeft",right:"ArrowRight",home:"Home",end:"End",page_up:"PageUp",page_down:"PageDown"},Do("abcdefghijklmnopqrstuvwxyz".split("").map(t=>[t,t]))),Do("0123456789".split("").map(t=>[t,t])))};function Do(t){return t.reduce((e,[n,r])=>Object.assign(Object.assign({},e),{[n]:r}),{})}var rr=class{constructor(e=document.documentElement,n=Su){this.logger=console,this.debug=!1,this.logDebugActivity=(r,i,s={})=>{this.debug&&this.logFormattedMessage(r,i,s)},this.element=e,this.schema=n,this.dispatcher=new vi(this),this.router=new Di(this),this.actionDescriptorFilters=Object.assign({},nu)}static start(e,n){let r=new this(e,n);return r.start(),r}async start(){await Tu(),this.logDebugActivity("application","starting"),this.dispatcher.start(),this.router.start(),this.logDebugActivity("application","start")}stop(){this.logDebugActivity("application","stopping"),this.dispatcher.stop(),this.router.stop(),this.logDebugActivity("application","stop")}register(e,n){this.load({identifier:e,controllerConstructor:n})}registerActionOption(e,n){this.actionDescriptorFilters[e]=n}load(e,...n){(Array.isArray(e)?e:[e,...n]).forEach(i=>{i.controllerConstructor.shouldLoad&&this.router.loadDefinition(i)})}unload(e,...n){(Array.isArray(e)?e:[e,...n]).forEach(i=>this.router.unloadIdentifier(i))}get controllers(){return this.router.contexts.map(e=>e.controller)}getControllerForElementAndIdentifier(e,n){let r=this.router.getContextForElementAndIdentifier(e,n);return r?r.controller:null}handleError(e,n,r){var i;this.logger.error(`%s

%o

%o`,n,e,r),(i=window.onerror)===null||i===void 0||i.call(window,n,"",0,0,e)}logFormattedMessage(e,n,r={}){r=Object.assign({application:this},r),this.logger.groupCollapsed(`${e} #${n}`),this.logger.log("details:",Object.assign({},r)),this.logger.groupEnd()}};function Tu(){return new Promise(t=>{document.readyState=="loading"?document.addEventListener("DOMContentLoaded",()=>t()):t()})}function $u(t){return An(t,"classes").reduce((n,r)=>Object.assign(n,Mu(r)),{})}function Mu(t){return{[`${t}Class`]:{get(){let{classes:e}=this;if(e.has(t))return e.get(t);{let n=e.getAttributeName(t);throw new Error(`Missing attribute "${n}"`)}}},[`${t}Classes`]:{get(){return this.classes.getAll(t)}},[`has${kn(t)}Class`]:{get(){return this.classes.has(t)}}}}function Nu(t){return An(t,"outlets").reduce((n,r)=>Object.assign(n,Fu(r)),{})}function Ro(t,e,n){return t.application.getControllerForElementAndIdentifier(e,n)}function Po(t,e,n){let r=Ro(t,e,n);if(r||(t.application.router.proposeToConnectScopeForElementAndIdentifier(e,n),r=Ro(t,e,n),r))return r}function Fu(t){let e=gi(t);return{[`${e}Outlet`]:{get(){let n=this.outlets.find(t),r=this.outlets.getSelectorForOutletName(t);if(n){let i=Po(this,n,t);if(i)return i;throw new Error(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`)}throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${r}".`)}},[`${e}Outlets`]:{get(){let n=this.outlets.findAll(t);return n.length>0?n.map(r=>{let i=Po(this,r,t);if(i)return i;console.warn(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`,r)}).filter(r=>r):[]}},[`${e}OutletElement`]:{get(){let n=this.outlets.find(t),r=this.outlets.getSelectorForOutletName(t);if(n)return n;throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${r}".`)}},[`${e}OutletElements`]:{get(){return this.outlets.findAll(t)}},[`has${kn(e)}Outlet`]:{get(){return this.outlets.has(t)}}}}function Lu(t){return An(t,"targets").reduce((n,r)=>Object.assign(n,Iu(r)),{})}function Iu(t){return{[`${t}Target`]:{get(){let e=this.targets.find(t);if(e)return e;throw new Error(`Missing target element "${t}" for "${this.identifier}" controller`)}},[`${t}Targets`]:{get(){return this.targets.findAll(t)}},[`has${kn(t)}Target`]:{get(){return this.targets.has(t)}}}}function Du(t){let e=mu(t,"values"),n={valueDescriptorMap:{get(){return e.reduce((r,i)=>{let s=zo(i,this.identifier),a=this.data.getAttributeNameForKey(s.key);return Object.assign(r,{[a]:s})},{})}}};return e.reduce((r,i)=>Object.assign(r,Ru(i)),n)}function Ru(t,e){let n=zo(t,e),{key:r,name:i,reader:s,writer:a}=n;return{[i]:{get(){let l=this.data.get(r);return l!==null?s(l):n.defaultValue},set(l){l===void 0?this.data.delete(r):this.data.set(r,a(l))}},[`has${kn(i)}`]:{get(){return this.data.has(r)||n.hasCustomDefaultValue}}}}function zo([t,e],n){return ju({controller:n,token:t,typeDefinition:e})}function ir(t){switch(t){case Array:return"array";case Boolean:return"boolean";case Number:return"number";case Object:return"object";case String:return"string"}}function En(t){switch(typeof t){case"boolean":return"boolean";case"number":return"number";case"string":return"string"}if(Array.isArray(t))return"array";if(Object.prototype.toString.call(t)==="[object Object]")return"object"}function Pu(t){let{controller:e,token:n,typeObject:r}=t,i=Fo(r.type),s=Fo(r.default),a=i&&s,l=i&&!s,u=!i&&s,c=ir(r.type),f=En(t.typeObject.default);if(l)return c;if(u)return f;if(c!==f){let d=e?`${e}.${n}`:n;throw new Error(`The specified default value for the Stimulus Value "${d}" must match the defined type "${c}". The provided default value of "${r.default}" is of type "${f}".`)}if(a)return c}function Bu(t){let{controller:e,token:n,typeDefinition:r}=t,s=Pu({controller:e,token:n,typeObject:r}),a=En(r),l=ir(r),u=s||a||l;if(u)return u;let c=e?`${e}.${r}`:n;throw new Error(`Unknown value type "${c}" for "${n}" value`)}function Vu(t){let e=ir(t);if(e)return Bo[e];let n=mi(t,"default"),r=mi(t,"type"),i=t;if(n)return i.default;if(r){let{type:s}=i,a=ir(s);if(a)return Bo[a]}return t}function ju(t){let{token:e,typeDefinition:n}=t,r=`${Uo(e)}-value`,i=Bu(t);return{type:i,key:r,name:Ri(r),get defaultValue(){return Vu(n)},get hasCustomDefaultValue(){return En(n)!==void 0},reader:Uu[i],writer:Vo[i]||Vo.default}}var Bo={get array(){return[]},boolean:!1,number:0,get object(){return{}},string:""},Uu={array(t){let e=JSON.parse(t);if(!Array.isArray(e))throw new TypeError(`expected value of type "array" but instead got value "${t}" of type "${En(e)}"`);return e},boolean(t){return!(t=="0"||String(t).toLowerCase()=="false")},number(t){return Number(t.replace(/_/g,""))},object(t){let e=JSON.parse(t);if(e===null||typeof e!="object"||Array.isArray(e))throw new TypeError(`expected value of type "object" but instead got value "${t}" of type "${En(e)}"`);return e},string(t){return t}},Vo={default:Ku,array:jo,object:jo};function jo(t){return JSON.stringify(t)}function Ku(t){return`${t}`}var qe=class{constructor(e){this.context=e}static get shouldLoad(){return!0}static afterLoad(e,n){}get application(){return this.context.application}get scope(){return this.context.scope}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get targets(){return this.scope.targets}get outlets(){return this.scope.outlets}get classes(){return this.scope.classes}get data(){return this.scope.data}initialize(){}connect(){}disconnect(){}dispatch(e,{target:n=this.element,detail:r={},prefix:i=this.identifier,bubbles:s=!0,cancelable:a=!0}={}){let l=i?`${i}:${e}`:e,u=new CustomEvent(l,{detail:r,bubbles:s,cancelable:a});return n.dispatchEvent(u),u}};qe.blessings=[$u,Lu,Du,Nu];qe.targets=[];qe.outlets=[];qe.values={};var sr=rr.start();sr.register("navigation",class extends qe{static get targets(){return["button"]}connect(){this.element.addEventListener("keydown",this.trapEscape.bind(this))}trapEscape(t){t.key==="Escape"&&this.close()}switch(){this.buttonTarget.getAttribute("aria-expanded")==="true"?this.buttonTarget.setAttribute("aria-expanded","false"):this.buttonTarget.setAttribute("aria-expanded","true")}close(){this.buttonTarget.setAttribute("aria-expanded","false"),this.buttonTarget.focus()}});sr.register("profile",class extends qe{static get targets(){return["sectionNatural","sectionLegal","firstName","lastName","legalName","controlAddress"]}initialize(){this.switchAddressForNode(this.controlAddressTarget);let t=document.querySelector('input[name="entity_type"][checked]');this.switchEntityTypeForNode(t)}switchAddress(t){this.switchAddressForNode(t.target)}switchAddressForNode(t){let e=document.querySelector("#address");e.hidden=!t.checked}switchEntityType(t){this.switchEntityTypeForNode(t.target)}switchEntityTypeForNode(t){t.value==="natural"?(this.sectionNaturalTarget.hidden=!1,this.sectionLegalTarget.hidden=!0,this.firstNameTarget.required=!0,this.lastNameTarget.required=!0,this.legalNameTarget.required=!1,this.controlAddressTarget.checked=this.controlAddressTarget.defaultChecked,this.controlAddressTarget.hidden=!1):(this.sectionNaturalTarget.hidden=!0,this.sectionLegalTarget.hidden=!1,this.firstNameTarget.required=!1,this.lastNameTarget.required=!1,this.legalNameTarget.required=!0,this.controlAddressTarget.checked=!0,this.controlAddressTarget.hidden=!0),this.switchAddressForNode(this.controlAddressTarget)}});sr.register("amount-selector",class extends qe{static get targets(){return["amount","radio","totalAmount"]}initialize(){let t=this.data.get("initialAmount");this.setAmount(t)}setAmount(t){let e=this.amountTarget;e.value=t,this.refreshTotalAmount()}select(t){let e=t.target;this.setAmount(e.dataset.value)}change(t){this.radioTargets.forEach(function(e){e.checked=!1}),this.refreshTotalAmount()}refreshTotalAmount(){if(this.totalAmountTarget){let t=parseInt(this.amountTarget.value,10),e=parseInt(this.data.get("countAccounts"),10);this.totalAmountTarget.innerHTML=t*e}}});sr.register("confirm",class extends qe{static get values(){return{message:String}}check(t){window.confirm(this.messageValue)||t.preventDefault()}});})();
//# sourceMappingURL=application.js.map
