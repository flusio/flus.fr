(()=>{var ns=Array.isArray,ha=Array.prototype.indexOf,jt=Array.prototype.includes,fa=Array.from,Sr=Object.keys,sr=Object.defineProperty,Bt=Object.getOwnPropertyDescriptor,da=Object.getOwnPropertyDescriptors,pa=Object.prototype,va=Array.prototype,is=Object.getPrototypeOf,Pi=Object.isExtensible,dt=()=>{};function ga(t){for(var e=0;e<t.length;e++)t[e]()}function ss(){var t,e,r=new Promise((n,i)=>{t=n,e=i});return{promise:r,resolve:t,reject:e}}var pe=2,Ut=4,Ir=8,Ln=1<<24,Pe=16,Ze=32,st=64,gn=128,Ne=512,ue=1024,fe=2048,Xe=4096,Ie=8192,Ue=16384,bt=32768,mn=1<<25,vt=65536,Tr=1<<17,ma=1<<18,Ft=1<<19,ba=1<<20,Tt=65536,$r=1<<21,Pt=1<<22,pt=1<<23,Vt=Symbol("$state"),ya=Symbol("legacy props"),wa=Symbol(""),os=Symbol("attributes"),bn=Symbol("class"),yn=Symbol("style"),wn=Symbol("text"),tr=Symbol("form reset"),Lr=new class extends Error{name="StaleReactionError";message="The reaction that called `getAbortSignal()` was re-run or destroyed"},ar=!!globalThis.document?.contentType&&globalThis.document.contentType.includes("xml"),lr=3,cr=8;function as(t){return t===this.v}function ls(t,e){return t!=t?e==e:t!==e||t!==null&&typeof t=="object"||typeof t=="function"}function _a(t){return!ls(t,this.v)}function Ea(t){throw new Error("https://svelte.dev/e/lifecycle_outside_component")}function ka(){throw new Error("https://svelte.dev/e/async_derived_orphan")}function Aa(t){throw new Error("https://svelte.dev/e/effect_in_teardown")}function xa(){throw new Error("https://svelte.dev/e/effect_in_unowned_derived")}function Ca(t){throw new Error("https://svelte.dev/e/effect_orphan")}function Oa(){throw new Error("https://svelte.dev/e/effect_update_depth_exceeded")}function Sa(){throw new Error("https://svelte.dev/e/hydration_failed")}function Ta(){throw new Error("https://svelte.dev/e/state_descriptors_fixed")}function $a(){throw new Error("https://svelte.dev/e/state_prototype_fixed")}function Ma(){throw new Error("https://svelte.dev/e/state_unsafe_mutation")}function Fa(){throw new Error("https://svelte.dev/e/svelte_boundary_reset_onerror")}var Na=1,Ia=2,Dn="[",cs="[!",Vi="[?",us="]",$t={},se=Symbol("uninitialized"),hs="http://www.w3.org/1999/xhtml",La="http://www.w3.org/2000/svg",Da="http://www.w3.org/1998/Math/MathML",Ra="@attach",Ee=null;function Kt(t){Ee=t}function at(t,e=!1,r){Ee={p:Ee,i:!1,c:null,e:null,s:t,x:null,r:T,l:null}}function lt(t){var e=Ee,r=e.e;if(r!==null){e.e=null;for(var n of r)Vs(n)}return t!==void 0&&(e.x=t),e.i=!0,Ee=e.p,t??{}}function fs(){return!0}var kt=[];function ds(){var t=kt;kt=[],ga(t)}function it(t){if(kt.length===0&&!rr){var e=kt;queueMicrotask(()=>{e===kt&&ds()})}kt.push(t)}function Ba(){for(;kt.length>0;)ds()}function Pa(){console.warn("https://svelte.dev/e/derived_inert")}function ur(t){console.warn("https://svelte.dev/e/hydration_mismatch")}function Va(){console.warn("https://svelte.dev/e/select_multiple_invalid_value")}function ja(){console.warn("https://svelte.dev/e/svelte_boundary_reset_noop")}var $=!1;function Je(t){$=t}var D;function be(t){if(t===null)throw ur(),$t;return D=t}function Mt(){return be(et(D))}function W(t){if($){if(et(D)!==null)throw ur(),$t;D=t}}function Rn(t=1){if($){for(var e=t,r=D;e--;)r=et(r);D=r}}function Bn(t=!0){for(var e=0,r=D;;){if(r.nodeType===cr){var n=r.data;if(n===us){if(e===0)return r;e-=1}else(n===Dn||n===cs||n[0]==="["&&!isNaN(Number(n.slice(1))))&&(e+=1)}var i=et(r);t&&r.remove(),r=i}}function ps(t){if(!t||t.nodeType!==cr)throw ur(),$t;return t.data}function nt(t){if(typeof t!="object"||t===null||Vt in t)return t;let e=is(t);if(e!==pa&&e!==va)return t;var r=new Map,n=ns(t),i=F(0),s=St,a=l=>{if(St===s)return l();var c=B,u=St;De(null),Yi(s);var f=l();return De(c),Yi(u),f};return n&&r.set("length",F(t.length)),new Proxy(t,{defineProperty(l,c,u){(!("value"in u)||u.configurable===!1||u.enumerable===!1||u.writable===!1)&&Ta();var f=r.get(c);return f===void 0?a(()=>{var p=F(u.value);return r.set(c,p),p}):_(f,u.value,!0),!0},deleteProperty(l,c){var u=r.get(c);if(u===void 0){if(c in l){let f=a(()=>F(se));r.set(c,f),nr(i)}}else _(u,se),nr(i);return!0},get(l,c,u){if(c===Vt)return t;var f=r.get(c),p=c in l;if(f===void 0&&(!p||Bt(l,c)?.writable)&&(f=a(()=>{var m=nt(p?l[c]:se),g=F(m);return g}),r.set(c,f)),f!==void 0){var d=o(f);return d===se?void 0:d}return Reflect.get(l,c,u)},getOwnPropertyDescriptor(l,c){var u=Reflect.getOwnPropertyDescriptor(l,c);if(u&&"value"in u){var f=r.get(c);f&&(u.value=o(f))}else if(u===void 0){var p=r.get(c),d=p?.v;if(p!==void 0&&d!==se)return{enumerable:!0,configurable:!0,value:d,writable:!0}}return u},has(l,c){if(c===Vt)return!0;var u=r.get(c),f=u!==void 0&&u.v!==se||Reflect.has(l,c);if(u!==void 0||T!==null&&(!f||Bt(l,c)?.writable)){u===void 0&&(u=a(()=>{var d=f?nt(l[c]):se,m=F(d);return m}),r.set(c,u));var p=o(u);if(p===se)return!1}return f},set(l,c,u,f){var p=r.get(c),d=c in l;if(n&&c==="length")for(var m=u;m<p.v;m+=1){var g=r.get(m+"");g!==void 0?_(g,se):m in l&&(g=a(()=>F(se)),r.set(m+"",g))}if(p===void 0)(!d||Bt(l,c)?.writable)&&(p=a(()=>F(void 0)),_(p,nt(u)),r.set(c,p));else{d=p.v!==se;var A=a(()=>nt(u));_(p,A)}var S=Reflect.getOwnPropertyDescriptor(l,c);if(S?.set&&S.set.call(f,u),!d){if(n&&typeof c=="string"){var N=r.get("length"),oe=Number(c);Number.isInteger(oe)&&oe>=N.v&&_(N,oe+1)}nr(i)}return!0},ownKeys(l){o(i);var c=Reflect.ownKeys(l).filter(p=>{var d=r.get(p);return d===void 0||d.v!==se});for(var[u,f]of r)f.v!==se&&!(u in l)&&c.push(u);return c},setPrototypeOf(){$a()}})}function ji(t){try{if(t!==null&&typeof t=="object"&&Vt in t)return t[Vt]}catch{}return t}function Ua(t,e){return Object.is(ji(t),ji(e))}var Ct,_n,vs,gs,ms;function En(){if(Ct===void 0){Ct=window,_n=document,vs=/Firefox/.test(navigator.userAgent);var t=Element.prototype,e=Node.prototype,r=Text.prototype;gs=Bt(e,"firstChild").get,ms=Bt(e,"nextSibling").get,Pi(t)&&(t[bn]=void 0,t[os]=null,t[yn]=void 0,t.__e=void 0),Pi(r)&&(r[wn]=void 0)}}function Ke(t=""){return document.createTextNode(t)}function Oe(t){return gs.call(t)}function et(t){return ms.call(t)}function Q(t,e){if(!$)return Oe(t);var r=Oe(D);if(r===null)r=D.appendChild(Ke());else if(e&&r.nodeType!==lr){var n=Ke();return r?.before(n),be(n),n}return e&&Dr(r),be(r),r}function Dt(t,e=!1){if(!$){var r=Oe(t);return r instanceof Comment&&r.data===""?et(r):r}if(e){if(D?.nodeType!==lr){var n=Ke();return D?.before(n),be(n),n}Dr(D)}return D}function G(t,e=1,r=!1){let n=$?D:t;for(var i;e--;)i=n,n=et(n);if(!$)return n;if(r){if(n?.nodeType!==lr){var s=Ke();return n===null?i?.after(s):n.before(s),be(s),s}Dr(n)}return be(n),n}function Ka(t){t.textContent=""}function za(){return!1}function Pn(t,e,r){return document.createElementNS(e??hs,t,void 0)}function Dr(t){if(t.nodeValue.length<65536)return;let e=t.nextSibling;for(;e!==null&&e.nodeType===lr;)e.remove(),t.nodeValue+=e.nodeValue,e=t.nextSibling}function bs(t){var e=T;if(e===null)return B.f|=pt,t;if((e.f&bt)===0&&(e.f&Ut)===0)throw t;ft(t,e)}function ft(t,e){for(;e!==null;){if((e.f&gn)!==0){if((e.f&bt)===0)throw t;try{e.b.error(t);return}catch(r){t=r}}e=e.parent}throw t}var Ha=-7169;function re(t,e){t.f=t.f&Ha|e}function Vn(t){(t.f&Ne)!==0||t.deps===null?re(t,ue):re(t,Xe)}function ys(t){if(t!==null)for(let e of t)(e.f&pe)===0||(e.f&Tt)===0||(e.f^=Tt,ys(e.deps))}function ws(t,e,r){(t.f&fe)!==0?e.add(t):(t.f&Xe)!==0&&r.add(t),ys(t.deps),re(t,ue)}function _s(t,e,r){if(t==null)return e(void 0),dt;let n=dr(()=>t.subscribe(e,r));return n.unsubscribe?()=>n.unsubscribe():n}var It=[];function qa(t,e=dt){let r=null,n=new Set;function i(l){if(ls(t,l)&&(t=l,r)){let c=!It.length;for(let u of n)u[1](),It.push(u,t);if(c){for(let u=0;u<It.length;u+=2)It[u][0](It[u+1]);It.length=0}}}function s(l){i(l(t))}function a(l,c=dt){let u=[l,c];return n.add(u),n.size===1&&(r=e(i,s)||dt),l(t),()=>{n.delete(u),n.size===0&&r&&(r(),r=null)}}return{set:i,update:s,subscribe:a}}function Qt(t){let e;return _s(t,r=>e=r)(),e}var kn=Symbol("unmounted");function Ui(t,e,r){let n=r[e]??={store:null,source:$s(void 0),unsubscribe:dt};if(n.store!==t&&!(kn in r))if(n.unsubscribe(),n.store=t??null,t==null)n.source.v=void 0,n.unsubscribe=dt;else{var i=!0;n.unsubscribe=_s(t,s=>{i?n.source.v=s:_(n.source,s)}),i=!1}return t&&kn in r?Qt(t):o(n.source)}function Ya(){let t={};function e(){Br(()=>{for(var r in t)t[r].unsubscribe();sr(t,kn,{enumerable:!1,value:!0})})}return[t,e]}var cn=null,Lt=null,M=null,An=null,Ve=null,xn=null,rr=!1,un=!1,Rt=null,xr=null,Ki=0;var Wa=1,gt=class t{id=Wa++;#e=!1;linked=!0;#t=null;#r=null;async_deriveds=new Map;current=new Map;previous=new Map;unblocked=new Set;#l=new Set;#n=new Set;#s=new Set;#i=0;#o=new Map;#f=null;#a=[];#p=[];#d=new Set;#c=new Set;#u=new Map;#h=new Set;is_fork=!1;#m=!1;#_(){if(this.is_fork)return!0;for(let n of this.#o.keys()){for(var e=n,r=!1;e.parent!==null;){if(this.#u.has(e)){r=!0;break}e=e.parent}if(!r)return!0}return!1}skip_effect(e){this.#u.has(e)||this.#u.set(e,{d:[],m:[]}),this.#h.delete(e)}unskip_effect(e,r=n=>this.schedule(n)){var n=this.#u.get(e);if(n){this.#u.delete(e);for(var i of n.d)re(i,fe),r(i);for(i of n.m)re(i,Xe),r(i)}this.#h.add(e)}#g(){if(this.#e=!0,Ki++>1e3&&(this.#w(),Ga()),!this.#_()){for(let c of this.#d)this.#c.delete(c),re(c,fe),this.schedule(c);for(let c of this.#c)re(c,Xe),this.schedule(c)}let e=this.#a;this.#a=[],this.apply();var r=Rt=[],n=[],i=xr=[];for(let c of e)try{this.#E(c,r,n)}catch(u){throw As(c),u}if(M=null,i.length>0){var s=t.ensure();for(let c of i)s.schedule(c)}if(Rt=null,xr=null,this.#_()){this.#v(n),this.#v(r);for(let[c,u]of this.#u)ks(c,u);i.length>0&&M.#g();return}let a=this.#k();if(a){a.#b(this);return}this.#d.clear(),this.#c.clear();for(let c of this.#l)c(this);this.#l.clear(),An=this,zi(n),zi(r),An=null,this.#f?.resolve();var l=M;if(this.linked&&this.#i===0&&this.#w(),this.#a.length>0){l===null&&(l=this,this.#y());let c=l;c.#a.push(...this.#a.filter(u=>!c.#a.includes(u)))}l!==null&&l.#g()}#E(e,r,n){e.f^=ue;for(var i=e.first;i!==null;){var s=i.f,a=(s&(Ze|st))!==0,l=a&&(s&ue)!==0,c=l||(s&Ie)!==0||this.#u.has(i);if(!c&&i.fn!==null){a?i.f^=ue:(s&Ut)!==0?r.push(i):fr(i)&&((s&Pe)!==0&&this.#c.add(i),zt(i));var u=i.first;if(u!==null){i=u;continue}}for(;i!==null;){var f=i.next;if(f!==null){i=f;break}i=i.parent}}}#k(){for(var e=this.#t;e!==null;){if(!e.is_fork){for(let[r,[,n]]of this.current)if(e.current.has(r)&&!n)return e}e=e.#t}return null}#b(e){for(let[n,i]of e.current)!this.previous.has(n)&&e.previous.has(n)&&this.previous.set(n,e.previous.get(n)),this.current.set(n,i);for(let[n,i]of e.async_deriveds){let s=this.async_deriveds.get(n);s&&i.promise.then(s.resolve)}let r=n=>{var i=n.reactions;if(i!==null)for(let l of i){var s=l.f;if((s&pe)!==0)r(l);else{var a=l;s&(Pt|Pe)&&!this.async_deriveds.has(a)&&(this.#c.delete(a),re(a,fe),this.schedule(a))}}};for(let n of this.current.keys())r(n);this.oncommit(()=>e.discard()),e.#w(),M=this,this.#g()}#v(e){for(var r=0;r<e.length;r+=1)ws(e[r],this.#d,this.#c)}capture(e,r,n=!1){e.v!==se&&!this.previous.has(e)&&this.previous.set(e,e.v),(e.f&pt)===0&&(this.current.set(e,[r,n]),Ve?.set(e,r)),this.is_fork||(e.v=r)}activate(){M=this}deactivate(){M=null,Ve=null}flush(){try{un=!0,M=this,this.#g()}finally{Ki=0,xn=null,Rt=null,xr=null,un=!1,M=null,Ve=null,Ot.clear()}}discard(){for(let e of this.#n)e(this);this.#n.clear(),this.#s.clear(),this.#w()}register_created_effect(e){this.#p.push(e)}#A(){this.#w();for(let f=cn;f!==null;f=f.#r){var e=f.id<this.id,r=[];for(let[p,[d,m]]of this.current){if(f.current.has(p)){var n=f.current.get(p)[0];if(e&&d!==n)f.current.set(p,[d,m]);else continue}r.push(p)}if(e)for(let[p,d]of this.async_deriveds){let m=f.async_deriveds.get(p);m&&d.promise.then(m.resolve)}if(f.#e){var i=[...f.current.keys()].filter(p=>!this.current.has(p));if(i.length===0)e&&f.discard();else if(r.length>0){if(e)for(let p of this.#h)f.unskip_effect(p,d=>{(d.f&(Pe|Pt))!==0?f.schedule(d):f.#v([d])});f.activate();var s=new Set,a=new Map;for(var l of r)Es(l,i,s,a);a=new Map;var c=[...f.current.keys()].filter(p=>this.current.has(p)?this.current.get(p)[0]!==p.v:!0);if(c.length>0)for(let p of this.#p)(p.f&(Ue|Ie|Tr))===0&&jn(p,c,a)&&((p.f&(Pt|Pe))!==0?(re(p,fe),f.schedule(p)):f.#d.add(p));if(f.#a.length>0){f.apply();for(var u of f.#a)f.#E(u,[],[]);f.#a=[]}f.deactivate()}}}}increment(e,r){if(this.#i+=1,e){let n=this.#o.get(r)??0;this.#o.set(r,n+1)}}decrement(e,r){if(this.#i-=1,e){let n=this.#o.get(r)??0;n===1?this.#o.delete(r):this.#o.set(r,n-1)}this.#m||(this.#m=!0,it(()=>{this.#m=!1,this.linked&&this.flush()}))}transfer_effects(e,r){for(let n of e)this.#d.add(n);for(let n of r)this.#c.add(n);e.clear(),r.clear()}oncommit(e){this.#l.add(e)}ondiscard(e){this.#n.add(e)}on_fork_commit(e){this.#s.add(e)}run_fork_commit_callbacks(){for(let e of this.#s)e(this);this.#s.clear()}settled(){return(this.#f??=ss()).promise}static ensure(){if(M===null){let e=M=new t;e.#y(),!un&&!rr&&it(()=>{e.#e||e.flush()})}return M}apply(){{Ve=null;return}}schedule(e){if(xn=e,e.b?.is_pending&&(e.f&(Ut|Ir|Ln))!==0&&(e.f&bt)===0){e.b.defer_effect(e);return}for(var r=e;r.parent!==null;){r=r.parent;var n=r.f;if(Rt!==null&&r===T&&(B===null||(B.f&pe)===0))return;if((n&(st|Ze))!==0){if((n&ue)===0)return;r.f^=ue}}this.#a.push(r)}#y(){Lt===null?cn=Lt=this:(Lt.#r=this,this.#t=Lt),Lt=this}#w(){var e=this.#t,r=this.#r;e===null?cn=r:e.#r=r,r===null?Lt=e:r.#t=e,this.linked=!1}};function q(t){var e=rr;rr=!0;try{for(var r;;){if(Ba(),M===null)return r;M.flush()}}finally{rr=e}}function Ga(){try{Oa()}catch(t){ft(t,xn)}}var rt=null;function zi(t){var e=t.length;if(e!==0){for(var r=0;r<e;){var n=t[r++];if((n.f&(Ue|Ie))===0&&fr(n)&&(rt=new Set,zt(n),n.deps===null&&n.first===null&&n.nodes===null&&n.teardown===null&&n.ac===null&&zs(n),rt?.size>0)){Ot.clear();for(let i of rt){if((i.f&(Ue|Ie))!==0)continue;let s=[i],a=i.parent;for(;a!==null;)rt.has(a)&&(rt.delete(a),s.push(a)),a=a.parent;for(let l=s.length-1;l>=0;l--){let c=s[l];(c.f&(Ue|Ie))===0&&zt(c)}}rt.clear()}}rt=null}}function Es(t,e,r,n){if(!r.has(t)&&(r.add(t),t.reactions!==null))for(let i of t.reactions){let s=i.f;(s&pe)!==0?Es(i,e,r,n):(s&(Pt|Pe))!==0&&(s&fe)===0&&jn(i,e,n)&&(re(i,fe),Un(i))}}function jn(t,e,r){let n=r.get(t);if(n!==void 0)return n;if(t.deps!==null)for(let i of t.deps){if(jt.call(e,i))return!0;if((i.f&pe)!==0&&jn(i,e,r))return r.set(i,!0),!0}return r.set(t,!1),!1}function Un(t){M.schedule(t)}function ks(t,e){if(!((t.f&Ze)!==0&&(t.f&ue)!==0)){(t.f&fe)!==0?e.d.push(t):(t.f&Xe)!==0&&e.m.push(t),re(t,ue);for(var r=t.first;r!==null;)ks(r,e),r=r.next}}function As(t){re(t,ue);for(var e=t.first;e!==null;)As(e),e=e.next}function Ja(t){let e=0,r=hr(0),n;return()=>{Hn()&&(o(r),Pr(()=>(e===0&&(n=dr(()=>t(()=>nr(r)))),e+=1,()=>{it(()=>{e-=1,e===0&&(n?.(),n=void 0,nr(r))})})))}}var Za=vt|Ft;function Xa(t,e,r,n){new Cn(t,e,r,n)}var Cn=class{parent;is_pending=!1;transform_error;#e;#t=$?D:null;#r;#l;#n;#s=null;#i=null;#o=null;#f=null;#a=0;#p=0;#d=!1;#c=new Set;#u=new Set;#h=null;#m=Ja(()=>(this.#h=hr(this.#a),()=>{this.#h=null}));constructor(e,r,n,i){this.#e=e,this.#r=r,this.#l=s=>{var a=T;a.b=this,a.f|=gn,n(s)},this.parent=T.b,this.transform_error=i??this.parent?.transform_error??(s=>s),this.#n=pr(()=>{if($){let s=this.#t;Mt();let a=s.data===cs;if(s.data.startsWith(Vi)){let c=JSON.parse(s.data.slice(Vi.length));this.#g(c)}else a?this.#E():this.#_()}else this.#k()},Za),$&&(this.#e=D)}#_(){try{this.#s=Be(()=>this.#l(this.#e))}catch(e){this.error(e)}}#g(e){let r=this.#r.failed;r&&(this.#o=Be(()=>{r(this.#e,()=>e,()=>()=>{})}))}#E(){let e=this.#r.pending;e&&(this.is_pending=!0,this.#i=Be(()=>e(this.#e)),it(()=>{var r=this.#f=document.createDocumentFragment(),n=Ke();r.append(n),this.#s=this.#v(()=>Be(()=>this.#l(n))),this.#p===0&&(this.#e.before(r),this.#f=null,ir(this.#i,()=>{this.#i=null}),this.#b(M))}))}#k(){try{if(this.is_pending=this.has_pending_snippet(),this.#p=0,this.#a=0,this.#s=Be(()=>{this.#l(this.#e)}),this.#p>0){var e=this.#f=document.createDocumentFragment();Ys(this.#s,e);let r=this.#r.pending;this.#i=Be(()=>r(this.#e))}else this.#b(M)}catch(r){this.error(r)}}#b(e){this.is_pending=!1,e.transfer_effects(this.#c,this.#u)}defer_effect(e){ws(e,this.#c,this.#u)}is_rendered(){return!this.is_pending&&(!this.parent||this.parent.is_rendered())}has_pending_snippet(){return!!this.#r.pending}#v(e){var r=T,n=B,i=Ee;Qe(this.#n),De(this.#n),Kt(this.#n.ctx);try{return gt.ensure(),e()}catch(s){return bs(s),null}finally{Qe(r),De(n),Kt(i)}}#A(e,r){if(!this.has_pending_snippet()){this.parent&&this.parent.#A(e,r);return}this.#p+=e,this.#p===0&&(this.#b(r),this.#i&&ir(this.#i,()=>{this.#i=null}),this.#f&&(this.#e.before(this.#f),this.#f=null))}update_pending_count(e,r){this.#A(e,r),this.#a+=e,!(!this.#h||this.#d)&&(this.#d=!0,it(()=>{this.#d=!1,this.#h&&Nr(this.#h,this.#a)}))}get_effect_pending(){return this.#m(),o(this.#h)}error(e){if(!this.#r.onerror&&!this.#r.failed)throw e;M?.is_fork?(this.#s&&M.skip_effect(this.#s),this.#i&&M.skip_effect(this.#i),this.#o&&M.skip_effect(this.#o),M.on_fork_commit(()=>{this.#y(e)})):this.#y(e)}#y(e){this.#s&&(de(this.#s),this.#s=null),this.#i&&(de(this.#i),this.#i=null),this.#o&&(de(this.#o),this.#o=null),$&&(be(this.#t),Rn(),be(Bn()));var r=this.#r.onerror;let n=this.#r.failed;var i=!1,s=!1;let a=()=>{if(i){ja();return}i=!0,s&&Fa(),this.#o!==null&&ir(this.#o,()=>{this.#o=null}),this.#v(()=>{this.#k()})},l=c=>{try{s=!0,r?.(c,a),s=!1}catch(u){ft(u,this.#n&&this.#n.parent)}n&&(this.#o=this.#v(()=>{try{return Be(()=>{var u=T;u.b=this,u.f|=gn,n(this.#e,()=>c,()=>a)})}catch(u){return ft(u,this.#n.parent),null}}))};it(()=>{var c;try{c=this.transform_error(e)}catch(u){ft(u,this.#n&&this.#n.parent);return}c!==null&&typeof c=="object"&&typeof c.then=="function"?c.then(l,u=>ft(u,this.#n&&this.#n.parent)):l(c)})}};function xs(t,e,r,n){let i=Kn;var s=t.filter(d=>!d.settled);if(r.length===0&&s.length===0){n(e.map(i));return}var a=T,l=Qa(),c=s.length===1?s[0].promise:s.length>1?Promise.all(s.map(d=>d.promise)):null;function u(d){if((a.f&Ue)===0){l();try{n(d)}catch(m){ft(m,a)}Mr()}}var f=Cs();if(r.length===0){c.then(()=>u(e.map(i))).finally(f);return}function p(){Promise.all(r.map(d=>el(d))).then(d=>u([...e.map(i),...d])).catch(d=>ft(d,a)).finally(f)}c?c.then(()=>{l(),p(),Mr()}):p()}function Qa(){var t=T,e=B,r=Ee,n=M;return function(s=!0){Qe(t),De(e),Kt(r),s&&(t.f&Ue)===0&&(n?.activate(),n?.apply())}}function Mr(t=!0){Qe(null),De(null),Kt(null),t&&M?.deactivate()}function Cs(){var t=T,e=t.b,r=M,n=e.is_rendered();return e.update_pending_count(1,r),r.increment(n,t),()=>{e.update_pending_count(-1,r),r.decrement(n,t)}}function Kn(t){var e=pe|fe;return T!==null&&(T.f|=Ft),{ctx:Ee,deps:null,effects:null,equals:as,f:e,fn:t,reactions:null,rv:0,v:se,wv:0,parent:T,ac:null}}var Er=Symbol("obsolete");function el(t,e,r){let n=T;n===null&&ka();var i=void 0,s=hr(se),a=!B,l=new Set;return fl(()=>{var c=T,u=ss();i=u.promise;try{Promise.resolve(t()).then(u.resolve,m=>{m!==Lr&&u.reject(m)}).finally(Mr)}catch(m){u.reject(m),Mr()}var f=M;if(a){if((c.f&bt)!==0)var p=Cs();if(n.b.is_rendered())f.async_deriveds.get(c)?.reject(Er);else for(let m of l.values())m.reject(Er);l.add(u),f.async_deriveds.set(c,u)}let d=(m,g=void 0)=>{p?.(),l.delete(u),g!==Er&&(f.activate(),g?(s.f|=pt,Nr(s,g)):((s.f&pt)!==0&&(s.f^=pt),Nr(s,m)),f.deactivate())};u.promise.then(d,m=>d(null,m||"unknown"))}),Br(()=>{for(let c of l)c.reject(Er)}),new Promise(c=>{function u(f){function p(){f===i?c(s):u(i)}f.then(p,p)}u(i)})}function me(t){let e=Kn(t);return Ns(e),e}function tl(t){var e=t.effects;if(e!==null){t.effects=null;for(var r=0;r<e.length;r+=1)de(e[r])}}function zn(t){var e,r=T,n=t.parent;if(!ot&&n!==null&&t.v!==se&&(n.f&(Ue|Ie))!==0)return Pa(),t.v;Qe(n);try{t.f&=~Tt,tl(t),e=Rs(t)}finally{Qe(r)}return e}function Os(t){var e=zn(t);if(!t.equals(e)&&(t.wv=Ls(),(!M?.is_fork||t.deps===null)&&(M!==null?(M.capture(t,e,!0),An?.capture(t,e,!0)):t.v=e,t.deps===null))){re(t,ue);return}ot||(Ve!==null?(Hn()||M?.is_fork)&&Ve.set(t,e):Vn(t))}function rl(t){if(t.effects!==null)for(let e of t.effects)(e.teardown||e.ac)&&(e.teardown?.(),e.ac?.abort(Lr),e.fn!==null&&(e.teardown=dt),e.ac=null,or(e,0),Yn(e))}function Ss(t){if(t.effects!==null)for(let e of t.effects)e.teardown&&e.fn!==null&&zt(e)}var Fr=new Set,Ot=new Map,Ts=!1;function hr(t,e){var r={f:0,v:t,reactions:null,equals:as,rv:0,wv:0};return r}function F(t,e){let r=hr(t);return Ns(r),r}function $s(t,e=!1,r=!0){let n=hr(t);return e||(n.equals=_a),n}function _(t,e,r=!1){B!==null&&(!je||(B.f&Tr)!==0)&&fs()&&(B.f&(pe|Pe|Pt|Tr))!==0&&(Le===null||!jt.call(Le,t))&&Ma();let n=r?nt(e):e;return Nr(t,n,xr)}function Nr(t,e,r=null){if(!t.equals(e)){Ot.set(t,ot?e:t.v);var n=gt.ensure();if(n.capture(t,e),(t.f&pe)!==0){let i=t;(t.f&fe)!==0&&zn(i),Ve===null&&Vn(i)}t.wv=Ls(),Ms(t,fe,r),T!==null&&(T.f&ue)!==0&&(T.f&(Ze|st))===0&&(Fe===null?ol([t]):Fe.push(t)),!n.is_fork&&Fr.size>0&&!Ts&&nl()}return e}function nl(){Ts=!1;for(let t of Fr){(t.f&ue)!==0&&re(t,Xe);let e;try{e=fr(t)}catch{e=!0}e&&zt(t)}Fr.clear()}function nr(t){_(t,t.v+1)}function Ms(t,e,r){var n=t.reactions;if(n!==null)for(var i=n.length,s=0;s<i;s++){var a=n[s],l=a.f,c=(l&fe)===0;if(c&&re(a,e),(l&Tr)!==0)Fr.add(a);else if((l&pe)!==0){var u=a;Ve?.delete(u),(l&Tt)===0&&(l&Ne&&(T===null||(T.f&$r)===0)&&(a.f|=Tt),Ms(u,Xe,r))}else if(c){var f=a;(l&Pe)!==0&&rt!==null&&rt.add(f),r!==null?r.push(f):Un(f)}}}function il(t,e){if(e){let r=document.body;t.autofocus=!0,it(()=>{document.activeElement===r&&t.focus()})}}var Hi=!1;function Fs(){Hi||(Hi=!0,document.addEventListener("reset",t=>{Promise.resolve().then(()=>{if(!t.defaultPrevented)for(let e of t.target.elements)e[tr]?.()})},{capture:!0}))}function Rr(t){var e=B,r=T;De(null),Qe(null);try{return t()}finally{De(e),Qe(r)}}function sl(t,e,r,n=r){t.addEventListener(e,()=>Rr(r));let i=t[tr];i?t[tr]=()=>{i(),n(!0)}:t[tr]=()=>n(!0),Fs()}var Cr=!1,ot=!1;function qi(t){ot=t}var B=null,je=!1;function De(t){B=t}var T=null;function Qe(t){T=t}var Le=null;function Ns(t){B!==null&&(Le===null?Le=[t]:Le.push(t))}var _e=null,xe=0,Fe=null;function ol(t){Fe=t}var Is=1,At=0,St=At;function Yi(t){St=t}function Ls(){return++Is}function fr(t){var e=t.f;if((e&fe)!==0)return!0;if(e&pe&&(t.f&=~Tt),(e&Xe)!==0){for(var r=t.deps,n=r.length,i=0;i<n;i++){var s=r[i];if(fr(s)&&Os(s),s.wv>t.wv)return!0}(e&Ne)!==0&&Ve===null&&re(t,ue)}return!1}function Ds(t,e,r=!0){var n=t.reactions;if(n!==null&&!(Le!==null&&jt.call(Le,t)))for(var i=0;i<n.length;i++){var s=n[i];(s.f&pe)!==0?Ds(s,e,!1):e===s&&(r?re(s,fe):(s.f&ue)!==0&&re(s,Xe),Un(s))}}function Rs(t){var e=_e,r=xe,n=Fe,i=B,s=Le,a=Ee,l=je,c=St,u=t.f;_e=null,xe=0,Fe=null,B=(u&(Ze|st))===0?t:null,Le=null,Kt(t.ctx),je=!1,St=++At,t.ac!==null&&(Rr(()=>{t.ac.abort(Lr)}),t.ac=null);try{t.f|=$r;var f=t.fn,p=f();t.f|=bt;var d=t.deps,m=M?.is_fork;if(_e!==null){var g;if(m||or(t,xe),d!==null&&xe>0)for(d.length=xe+_e.length,g=0;g<_e.length;g++)d[xe+g]=_e[g];else t.deps=d=_e;if(Hn()&&(t.f&Ne)!==0)for(g=xe;g<d.length;g++)(d[g].reactions??=[]).push(t)}else!m&&d!==null&&xe<d.length&&(or(t,xe),d.length=xe);if(fs()&&Fe!==null&&!je&&d!==null&&(t.f&(pe|Xe|fe))===0)for(g=0;g<Fe.length;g++)Ds(Fe[g],t);if(i!==null&&i!==t){if(At++,i.deps!==null)for(let A=0;A<r;A+=1)i.deps[A].rv=At;if(e!==null)for(let A of e)A.rv=At;Fe!==null&&(n===null?n=Fe:n.push(...Fe))}return(t.f&pt)!==0&&(t.f^=pt),p}catch(A){return bs(A)}finally{t.f^=$r,_e=e,xe=r,Fe=n,B=i,Le=s,Kt(a),je=l,St=c}}function al(t,e){let r=e.reactions;if(r!==null){var n=ha.call(r,t);if(n!==-1){var i=r.length-1;i===0?r=e.reactions=null:(r[n]=r[i],r.pop())}}if(r===null&&(e.f&pe)!==0&&(_e===null||!jt.call(_e,e))){var s=e;(s.f&Ne)!==0&&(s.f^=Ne,s.f&=~Tt),s.v!==se&&Vn(s),rl(s),or(s,0)}}function or(t,e){var r=t.deps;if(r!==null)for(var n=e;n<r.length;n++)al(t,r[n])}function zt(t){var e=t.f;if((e&Ue)===0){re(t,ue);var r=T,n=Cr;T=t,Cr=!0;try{(e&(Pe|Ln))!==0?dl(t):Yn(t),Us(t);var i=Rs(t);t.teardown=typeof i=="function"?i:null,t.wv=Is;var s}finally{Cr=n,T=r}}}async function xt(){await Promise.resolve(),q()}function o(t){var e=t.f,r=(e&pe)!==0;if(B!==null&&!je){var n=T!==null&&(T.f&Ue)!==0;if(!n&&(Le===null||!jt.call(Le,t))){var i=B.deps;if((B.f&$r)!==0)t.rv<At&&(t.rv=At,_e===null&&i!==null&&i[xe]===t?xe++:_e===null?_e=[t]:_e.push(t));else{(B.deps??=[]).push(t);var s=t.reactions;s===null?t.reactions=[B]:jt.call(s,B)||s.push(B)}}}if(ot&&Ot.has(t))return Ot.get(t);if(r){var a=t;if(ot){var l=a.v;return((a.f&ue)===0&&a.reactions!==null||Ps(a))&&(l=zn(a)),Ot.set(a,l),l}var c=(a.f&Ne)===0&&!je&&B!==null&&(Cr||(B.f&Ne)!==0),u=(a.f&bt)===0;fr(a)&&(c&&(a.f|=Ne),Os(a)),c&&!u&&(Ss(a),Bs(a))}if(Ve?.has(t))return Ve.get(t);if((t.f&pt)!==0)throw t.v;return t.v}function Bs(t){if(t.f|=Ne,t.deps!==null)for(let e of t.deps)(e.reactions??=[]).push(t),(e.f&pe)!==0&&(e.f&Ne)===0&&(Ss(e),Bs(e))}function Ps(t){if(t.v===se)return!0;if(t.deps===null)return!1;for(let e of t.deps)if(Ot.has(e)||(e.f&pe)!==0&&Ps(e))return!0;return!1}function dr(t){var e=je;try{return je=!0,t()}finally{je=e}}function ll(t){T===null&&(B===null&&Ca(),xa()),ot&&Aa()}function cl(t,e){var r=e.last;r===null?e.last=e.first=t:(r.next=t,t.prev=r,e.last=t)}function ze(t,e){var r=T;r!==null&&(r.f&Ie)!==0&&(t|=Ie);var n={ctx:Ee,deps:null,nodes:null,f:t|fe|Ne,first:null,fn:e,last:null,next:null,parent:r,b:r&&r.b,prev:null,teardown:null,wv:0,ac:null};M?.register_created_effect(n);var i=n;if((t&Ut)!==0)Rt!==null?Rt.push(n):gt.ensure().schedule(n);else if(e!==null){try{zt(n)}catch(a){throw de(n),a}i.deps===null&&i.teardown===null&&i.nodes===null&&i.first===i.last&&(i.f&Ft)===0&&(i=i.first,(t&Pe)!==0&&(t&vt)!==0&&i!==null&&(i.f|=vt))}if(i!==null&&(i.parent=r,r!==null&&cl(i,r),B!==null&&(B.f&pe)!==0&&(t&st)===0)){var s=B;(s.effects??=[]).push(i)}return n}function Hn(){return B!==null&&!je}function Br(t){let e=ze(Ir,null);return re(e,ue),e.teardown=t,e}function Ce(t){ll();var e=T.f,r=!B&&(e&Ze)!==0&&(e&bt)===0;if(r){var n=Ee;(n.e??=[]).push(t)}else return Vs(t)}function Vs(t){return ze(Ut|ba,t)}function ul(t){gt.ensure();let e=ze(st|Ft,t);return()=>{de(e)}}function hl(t){gt.ensure();let e=ze(st|Ft,t);return(r={})=>new Promise(n=>{r.outro?ir(e,()=>{de(e),n(void 0)}):(de(e),n(void 0))})}function qn(t){return ze(Ut,t)}function fl(t){return ze(Pt|Ft,t)}function Pr(t,e=0){return ze(Ir|e,t)}function ve(t,e=[],r=[],n=[]){xs(n,e,r,i=>{ze(Ir,()=>t(...i.map(o)))})}function pr(t,e=0){var r=ze(Pe|e,t);return r}function js(t,e=0){var r=ze(Ln|e,t);return r}function Be(t){return ze(Ze|Ft,t)}function Us(t){var e=t.teardown;if(e!==null){let r=ot,n=B;qi(!0),De(null);try{e.call(null)}finally{qi(r),De(n)}}}function Yn(t,e=!1){var r=t.first;for(t.first=t.last=null;r!==null;){let i=r.ac;i!==null&&Rr(()=>{i.abort(Lr)});var n=r.next;(r.f&st)!==0?r.parent=null:de(r,e),r=n}}function dl(t){for(var e=t.first;e!==null;){var r=e.next;(e.f&Ze)===0&&de(e),e=r}}function de(t,e=!0){var r=!1;(e||(t.f&ma)!==0)&&t.nodes!==null&&t.nodes.end!==null&&(Ks(t.nodes.start,t.nodes.end),r=!0),re(t,mn),Yn(t,e&&!r),or(t,0);var n=t.nodes&&t.nodes.t;if(n!==null)for(let s of n)s.stop();Us(t),t.f^=mn,t.f|=Ue;var i=t.parent;i!==null&&i.first!==null&&zs(t),t.next=t.prev=t.teardown=t.ctx=t.deps=t.fn=t.nodes=t.ac=t.b=null}function Ks(t,e){for(;t!==null;){var r=t===e?null:et(t);t.remove(),t=r}}function zs(t){var e=t.parent,r=t.prev,n=t.next;r!==null&&(r.next=n),n!==null&&(n.prev=r),e!==null&&(e.first===t&&(e.first=n),e.last===t&&(e.last=r))}function ir(t,e,r=!0){var n=[];Hs(t,n,!0);var i=()=>{r&&de(t),e&&e()},s=n.length;if(s>0){var a=()=>--s||i();for(var l of n)l.out(a)}else i()}function Hs(t,e,r){if((t.f&Ie)===0){t.f^=Ie;var n=t.nodes&&t.nodes.t;if(n!==null)for(let l of n)(l.is_global||r)&&e.push(l);for(var i=t.first;i!==null;){var s=i.next;if((i.f&st)===0){var a=(i.f&vt)!==0||(i.f&Ze)!==0&&(t.f&Pe)!==0;Hs(i,e,a?r:!1)}i=s}}}function pl(t){qs(t,!0)}function qs(t,e){if((t.f&Ie)!==0){t.f^=Ie,(t.f&ue)===0&&(re(t,fe),gt.ensure().schedule(t));for(var r=t.first;r!==null;){var n=r.next,i=(r.f&vt)!==0||(r.f&Ze)!==0;qs(r,i?e:!1),r=n}var s=t.nodes&&t.nodes.t;if(s!==null)for(let a of s)(a.is_global||e)&&a.in()}}function Ys(t,e){if(t.nodes)for(var r=t.nodes.start,n=t.nodes.end;r!==null;){var i=r===n?null:et(r);e.append(r),r=i}}function Wi(t){let e={get:r=>Qt(e.store)[r],set:(r,n)=>{typeof r=="string"?Object.assign(Qt(e.store),{[r]:n}):Object.assign(Qt(e.store),r),e.store.set(Qt(e.store))},store:qa(t)};return e}globalThis.$altcha=globalThis.$altcha||{algorithms:new Map,defaults:Wi({}),i18n:Wi({}),instances:new Set,plugins:new Set};var vl={ariaLinkLabel:"Altcha (official website)",cancel:"Cancel",enterCode:"Enter code",enterCodeAria:"Enter code you hear. Press Space to play audio.",enterCodeFromImage:"To proceed, please enter the code from the image below.",error:"Verification failed. Try again later.",expired:"Verification expired. Try again.",footer:'Protected by <a href="https://altcha.org/" tabindex="-1" target="_blank" aria-label="Altcha (official website)">ALTCHA</a>',getAudioChallenge:"Get an audio challenge",label:"I'm not a robot",loading:"Loading...",reload:"Reload",verify:"Verify",verificationRequired:"Verification required!",verified:"Verified",verifying:"Verifying...",waitAlert:"Verifying... please wait."};"$altcha"in globalThis&&globalThis.$altcha.i18n.set("en",vl);var gl="5";typeof window<"u"&&((window.__svelte??={}).v??=new Set).add(gl);var er=Symbol("events"),Ws=new Set,On=new Set;function Gs(t,e,r,n={}){function i(s){if(n.capture||Sn.call(e,s),!s.cancelBubble)return Rr(()=>r?.call(this,s))}return t.startsWith("pointer")||t.startsWith("touch")||t==="wheel"?it(()=>{e.addEventListener(t,i,n)}):e.addEventListener(t,i,n),i}function ce(t,e,r,n,i){var s={capture:n,passive:i},a=Gs(t,e,r,s);(e===document.body||e===window||e===document||e instanceof HTMLMediaElement)&&Br(()=>{e.removeEventListener(t,a,s)})}function Vr(t,e,r){(e[er]??={})[t]=r}function jr(t){for(var e=0;e<t.length;e++)Ws.add(t[e]);for(var r of On)r(t)}var Gi=null;function Sn(t){var e=this,r=e.ownerDocument,n=t.type,i=t.composedPath?.()||[],s=i[0]||t.target;Gi=t;var a=0,l=Gi===t&&t[er];if(l){var c=i.indexOf(l);if(c!==-1&&(e===document||e===window)){t[er]=e;return}var u=i.indexOf(e);if(u===-1)return;c<=u&&(a=c)}if(s=i[a]||t.target,s!==e){sr(t,"currentTarget",{configurable:!0,get(){return s||r}});var f=B,p=T;De(null),Qe(null);try{for(var d,m=[];s!==null;){var g=s.assignedSlot||s.parentNode||s.host||null;try{var A=s[er]?.[n];A!=null&&(!s.disabled||t.target===s)&&A.call(s,t)}catch(S){d?m.push(S):d=S}if(t.cancelBubble||g===e||g===null)break;s=g}if(d){for(let S of m)queueMicrotask(()=>{throw S});throw d}}finally{t[er]=e,delete t.currentTarget,De(f),Qe(p)}}}var ml=globalThis?.window?.trustedTypes&&globalThis.window.trustedTypes.createPolicy("svelte-trusted-html",{createHTML:t=>t});function bl(t){return ml?.createHTML(t)??t}function Js(t){var e=Pn("template");return e.innerHTML=bl(t.replaceAll("<!>","<!---->")),e.content}function Se(t,e){var r=T;r.nodes===null&&(r.nodes={start:t,end:e,a:null,t:null})}function Z(t,e){var r=(e&Na)!==0,n=(e&Ia)!==0,i,s=!t.startsWith("<!>");return()=>{if($)return Se(D,null),D;i===void 0&&(i=Js(s?t:"<!>"+t),r||(i=Oe(i)));var a=n||vs?document.importNode(i,!0):i.cloneNode(!0);if(r){var l=Oe(a),c=a.lastChild;Se(l,c)}else Se(a,a);return a}}function yl(t,e,r="svg"){var n=!t.startsWith("<!>"),i=`<${r}>${n?t:"<!>"+t}</${r}>`,s;return()=>{if($)return Se(D,null),D;if(!s){var a=Js(i),l=Oe(a);s=Oe(l)}var c=s.cloneNode(!0);return Se(c,c),c}}function Wn(t,e){return yl(t,e,"svg")}function kr(t=""){if(!$){var e=Ke(t+"");return Se(e,e),e}var r=D;return r.nodeType!==lr?(r.before(r=Ke()),be(r)):Dr(r),Se(r,r),r}function Ji(){if($)return Se(D,null),D;var t=document.createDocumentFragment(),e=document.createComment(""),r=Ke();return t.append(e,r),Se(e,r),t}function L(t,e){if($){var r=T;((r.f&bt)===0||r.nodes.end===null)&&(r.nodes.end=D),Mt();return}t!==null&&t.before(e)}function wl(t){return t.endsWith("capture")&&t!=="gotpointercapture"&&t!=="lostpointercapture"}var _l=["beforeinput","click","change","dblclick","contextmenu","focusin","focusout","input","keydown","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","pointerdown","pointermove","pointerout","pointerover","pointerup","touchend","touchmove","touchstart"];function El(t){return _l.includes(t)}var kl={formnovalidate:"formNoValidate",ismap:"isMap",nomodule:"noModule",playsinline:"playsInline",readonly:"readOnly",defaultvalue:"defaultValue",defaultchecked:"defaultChecked",srcobject:"srcObject",novalidate:"noValidate",allowfullscreen:"allowFullscreen",disablepictureinpicture:"disablePictureInPicture",disableremoteplayback:"disableRemotePlayback"};function Al(t){return t=t.toLowerCase(),kl[t]??t}var xl=["touchstart","touchmove"];function Cl(t){return xl.includes(t)}function Ge(t,e){var r=e==null?"":typeof e=="object"?`${e}`:e;r!==(t[wn]??=t.nodeValue)&&(t[wn]=r,t.nodeValue=`${r}`)}function Zs(t,e){return Xs(t,e)}function Ol(t,e){En(),e.intro=e.intro??!1;let r=e.target,n=$,i=D;try{for(var s=Oe(r);s&&(s.nodeType!==cr||s.data!==Dn);)s=et(s);if(!s)throw $t;Je(!0),be(s);let a=Xs(t,{...e,anchor:s});return Je(!1),a}catch(a){if(a instanceof Error&&a.message.split(`
`).some(l=>l.startsWith("https://svelte.dev/e/")))throw a;return a!==$t&&console.warn("Failed to hydrate: ",a),e.recover===!1&&Sa(),En(),Ka(r),Je(!1),Zs(t,e)}finally{Je(n),be(i)}}var Ar=new Map;function Xs(t,{target:e,anchor:r,props:n={},events:i,context:s,intro:a=!0,transformError:l}){En();var c=void 0,u=hl(()=>{var f=r??e.appendChild(Ke());Xa(f,{pending:()=>{}},m=>{at({});var g=Ee;if(s&&(g.c=s),i&&(n.$$events=i),$&&Se(m,null),c=t(m,n)||{},$&&(T.nodes.end=D,D===null||D.nodeType!==cr||D.data!==us))throw ur(),$t;lt()},l);var p=new Set,d=m=>{for(var g=0;g<m.length;g++){var A=m[g];if(!p.has(A)){p.add(A);var S=Cl(A);for(let ne of[e,document]){var N=Ar.get(ne);N===void 0&&(N=new Map,Ar.set(ne,N));var oe=N.get(A);oe===void 0?(ne.addEventListener(A,Sn,{passive:S}),N.set(A,1)):N.set(A,oe+1)}}}};return d(fa(Ws)),On.add(d),()=>{for(var m of p)for(let S of[e,document]){var g=Ar.get(S),A=g.get(m);--A==0?(S.removeEventListener(m,Sn),g.delete(m),g.size===0&&Ar.delete(S)):g.set(m,A)}On.delete(d),f!==r&&f.parentNode?.removeChild(f)}});return Tn.set(c,u),c}var Tn=new WeakMap;function Sl(t,e){let r=Tn.get(t);return r?(Tn.delete(t),r(e)):Promise.resolve()}var Ht=class{anchor;#e=new Map;#t=new Map;#r=new Map;#l=new Set;#n=!0;constructor(e,r=!0){this.anchor=e,this.#n=r}#s=e=>{if(this.#e.has(e)){var r=this.#e.get(e),n=this.#t.get(r);if(n)pl(n),this.#l.delete(r);else{var i=this.#r.get(r);i&&(this.#t.set(r,i.effect),this.#r.delete(r),i.fragment.lastChild.remove(),this.anchor.before(i.fragment),n=i.effect)}for(let[s,a]of this.#e){if(this.#e.delete(s),s===e)break;let l=this.#r.get(a);l&&(de(l.effect),this.#r.delete(a))}for(let[s,a]of this.#t){if(s===r||this.#l.has(s))continue;let l=()=>{if(Array.from(this.#e.values()).includes(s)){var u=document.createDocumentFragment();Ys(a,u),u.append(Ke()),this.#r.set(s,{effect:a,fragment:u})}else de(a);this.#l.delete(s),this.#t.delete(s)};this.#n||!n?(this.#l.add(s),ir(a,l,!1)):l()}}};#i=e=>{this.#e.delete(e);let r=Array.from(this.#e.values());for(let[n,i]of this.#r)r.includes(n)||(de(i.effect),this.#r.delete(n))};ensure(e,r){var n=M,i=za();if(r&&!this.#t.has(e)&&!this.#r.has(e))if(i){var s=document.createDocumentFragment(),a=Ke();s.append(a),this.#r.set(e,{effect:Be(()=>r(a)),fragment:s})}else this.#t.set(e,Be(()=>r(this.anchor)));if(this.#e.set(n,e),i){for(let[l,c]of this.#t)l===e?n.unskip_effect(c):n.skip_effect(c);for(let[l,c]of this.#r)l===e?n.unskip_effect(c.effect):n.skip_effect(c.effect);n.oncommit(this.#s),n.ondiscard(this.#i)}else $&&(this.anchor=D),this.#s(n)}};function Tl(t,e,...r){var n=new Ht(t);pr(()=>{let i=e()??null;n.ensure(i,i&&(s=>i(s,...r)))},vt)}function Gn(t){Ee===null&&Ea(),Ce(()=>{let e=dr(t);if(typeof e=="function")return e})}function le(t,e,r=!1){var n;$&&(n=D,Mt());var i=new Ht(t),s=r?vt:0;function a(l,c){if($){var u=ps(n);if(l!==parseInt(u.substring(1))){var f=Bn();be(f),i.anchor=f,Je(!1),i.ensure(l,c),Je(!0);return}}i.ensure(l,c)}pr(()=>{var l=!1;e((c,u=0)=>{l=!0,a(u,c)}),l||a(-1,null)},s)}var $l=Symbol("NaN");function Ml(t,e,r){$&&Mt();var n=new Ht(t);pr(()=>{var i=e();i!==i&&(i=$l),n.ensure(i,r)})}function Qs(t,e,r=!1,n=!1,i=!1,s=!1){var a=t,l="";if(r){var c=t;$&&(a=be(Oe(c)))}ve(()=>{var u=T;if(l===(l=e()??"")){$&&Mt();return}if(r&&!$){u.nodes=null,c.innerHTML=l,l!==""&&Se(Oe(c),c.lastChild);return}if(u.nodes!==null&&(Ks(u.nodes.start,u.nodes.end),u.nodes=null),l!==""){if($){D.data;for(var f=Mt(),p=f;f!==null&&(f.nodeType!==cr||f.data!=="");)p=f,f=et(f);if(f===null)throw ur(),$t;Se(D,p),a=be(f);return}var d=n?La:i?Da:void 0,m=Pn(n?"svg":i?"math":"template",d);m.innerHTML=l;var g=n||i?m:m.content;if(Se(Oe(g),g.lastChild),n||i)for(;Oe(g);)a.before(Oe(g));else a.before(g)}})}function Fl(t,e,r){var n;$&&(n=D,Mt());var i=new Ht(t);pr(()=>{var s=e()??null;if($){var a=ps(n),l=a===Dn,c=s!==null;if(l!==c){var u=Bn();be(u),i.anchor=u,Je(!1),i.ensure(s,s&&(f=>r(f,s))),Je(!0);return}}i.ensure(s,s&&(f=>r(f,s)))},vt)}function Nl(t,e){var r=void 0,n;js(()=>{r!==(r=e())&&(n&&(de(n),n=null),r&&(n=Be(()=>{qn(()=>r(t))})))})}function eo(t){var e,r,n="";if(typeof t=="string"||typeof t=="number")n+=t;else if(typeof t=="object")if(Array.isArray(t)){var i=t.length;for(e=0;e<i;e++)t[e]&&(r=eo(t[e]))&&(n&&(n+=" "),n+=r)}else for(r in t)t[r]&&(n&&(n+=" "),n+=r);return n}function Il(){for(var t,e,r=0,n="",i=arguments.length;r<i;r++)(t=arguments[r])&&(e=eo(t))&&(n&&(n+=" "),n+=e);return n}function Ll(t){return typeof t=="object"?Il(t):t??""}var Zi=[...` 	
\r\f\xA0\v\uFEFF`];function Dl(t,e,r){var n=t==null?"":""+t;if(r){for(var i of Object.keys(r))if(r[i])n=n?n+" "+i:i;else if(n.length)for(var s=i.length,a=0;(a=n.indexOf(i,a))>=0;){var l=a+s;(a===0||Zi.includes(n[a-1]))&&(l===n.length||Zi.includes(n[l]))?n=(a===0?"":n.substring(0,a))+n.substring(l+1):a=l}}return n===""?null:n}function Xi(t,e=!1){var r=e?" !important;":";",n="";for(var i of Object.keys(t)){var s=t[i];s!=null&&s!==""&&(n+=" "+i+": "+s+r)}return n}function hn(t){return t[0]!=="-"||t[1]!=="-"?t.toLowerCase():t}function Rl(t,e){if(e){var r="",n,i;if(Array.isArray(e)?(n=e[0],i=e[1]):n=e,t){t=String(t).replaceAll(/\s*\/\*.*?\*\/\s*/g,"").trim();var s=!1,a=0,l=!1,c=[];n&&c.push(...Object.keys(n).map(hn)),i&&c.push(...Object.keys(i).map(hn));var u=0,f=-1;let A=t.length;for(var p=0;p<A;p++){var d=t[p];if(l?d==="/"&&t[p-1]==="*"&&(l=!1):s?s===d&&(s=!1):d==="/"&&t[p+1]==="*"?l=!0:d==='"'||d==="'"?s=d:d==="("?a++:d===")"&&a--,!l&&s===!1&&a===0){if(d===":"&&f===-1)f=p;else if(d===";"||p===A-1){if(f!==-1){var m=hn(t.substring(u,f).trim());if(!c.includes(m)){d!==";"&&p++;var g=t.substring(u,p).trim();r+=" "+g+";"}}u=p+1,f=-1}}}}return n&&(r+=Xi(n)),i&&(r+=Xi(i,!0)),r=r.trim(),r===""?null:r}return t==null?null:String(t)}function Bl(t,e,r,n,i,s){var a=t[bn];if($||a!==r||a===void 0){var l=Dl(r,n,s);(!$||l!==t.getAttribute("class"))&&(l==null?t.removeAttribute("class"):e?t.className=l:t.setAttribute("class",l)),t[bn]=r}else if(s&&i!==s)for(var c in s){var u=!!s[c];(i==null||u!==!!i[c])&&t.classList.toggle(c,u)}return s}function fn(t,e={},r,n){for(var i in r){var s=r[i];e[i]!==s&&(r[i]==null?t.style.removeProperty(i):t.style.setProperty(i,s,n))}}function Pl(t,e,r,n){var i=t[yn];if($||i!==e){var s=Rl(e,n);(!$||s!==t.getAttribute("style"))&&(s==null?t.removeAttribute("style"):t.style.cssText=s),t[yn]=e}else n&&(Array.isArray(n)?(fn(t,r?.[0],n[0]),fn(t,r?.[1],n[1],"important")):fn(t,r,n));return n}function $n(t,e,r=!1){if(t.multiple){if(e==null)return;if(!ns(e))return Va();for(var n of t.options)n.selected=e.includes(Qi(n));return}for(n of t.options){var i=Qi(n);if(Ua(i,e)){n.selected=!0;return}}(!r||e!==void 0)&&(t.selectedIndex=-1)}function Vl(t){var e=new MutationObserver(()=>{$n(t,t.__value)});e.observe(t,{childList:!0,subtree:!0,attributes:!0,attributeFilter:["value"]}),Br(()=>{e.disconnect()})}function Qi(t){return"__value"in t?t.__value:t.value}var Zt=Symbol("class"),Xt=Symbol("style"),to=Symbol("is custom element"),ro=Symbol("is html"),jl=ar?"link":"LINK",Ul=ar?"input":"INPUT",Kl=ar?"option":"OPTION",zl=ar?"select":"SELECT",Hl=ar?"progress":"PROGRESS";function Jn(t){if($){var e=!1,r=()=>{if(!e){if(e=!0,t.hasAttribute("value")){var n=t.value;U(t,"value",null),t.value=n}if(t.hasAttribute("checked")){var i=t.checked;U(t,"checked",null),t.checked=i}}};t[tr]=r,it(r),Fs()}}function ql(t,e){var r=Zn(t);r.value===(r.value=e??void 0)||t.value===e&&(e!==0||t.nodeName!==Hl)||(t.value=e??"")}function Yl(t,e){e?t.hasAttribute("selected")||t.setAttribute("selected",""):t.removeAttribute("selected")}function U(t,e,r,n){var i=Zn(t);$&&(i[e]=t.getAttribute(e),e==="src"||e==="srcset"||e==="href"&&t.nodeName===jl)||i[e]!==(i[e]=r)&&(e==="loading"&&(t[wa]=r),r==null?t.removeAttribute(e):typeof r!="string"&&no(t).includes(e)?t[e]=r:t.setAttribute(e,r))}function Wl(t,e,r,n,i=!1,s=!1){if($&&i&&t.nodeName===Ul){var a=t,l=a.type==="checkbox"?"defaultChecked":"defaultValue";l in r||Jn(a)}var c=Zn(t),u=c[to],f=!c[ro];let p=$&&u;p&&Je(!1);var d=e||{},m=t.nodeName===Kl;for(var g in e)g in r||(r[g]=null);r.class?r.class=Ll(r.class):r[Zt]&&(r.class=null),r[Xt]&&(r.style??=null);var A=no(t);for(let k in r){let I=r[k];if(m&&k==="value"&&I==null){t.value=t.__value="",d[k]=I;continue}if(k==="class"){var S=t.namespaceURI==="http://www.w3.org/1999/xhtml";Bl(t,S,I,n,e?.[Zt],r[Zt]),d[k]=I,d[Zt]=r[Zt];continue}if(k==="style"){Pl(t,I,e?.[Xt],r[Xt]),d[k]=I,d[Xt]=r[Xt];continue}var N=d[k];if(!(I===N&&!(I===void 0&&t.hasAttribute(k)))){d[k]=I;var oe=k[0]+k[1];if(oe!=="$$")if(oe==="on"){let ee={},P="$$"+k,R=k.slice(2);var ne=El(R);if(wl(R)&&(R=R.slice(0,-7),ee.capture=!0),!ne&&N){if(I!=null)continue;t.removeEventListener(R,d[P],ee),d[P]=null}if(ne)Vr(R,t,I),jr([R]);else if(I!=null){let Re=function(ge){d[k].call(this,ge)};var ae=Re;d[P]=Gs(R,t,Re,ee)}}else if(k==="style")U(t,k,I);else if(k==="autofocus")il(t,!!I);else if(!u&&(k==="__value"||k==="value"&&I!=null))t.value=t.__value=I;else if(k==="selected"&&m)Yl(t,I);else{var K=k;f||(K=Al(K));var He=K==="defaultValue"||K==="defaultChecked";if(I==null&&!u&&!He)if(c[k]=null,K==="value"||K==="checked"){let ee=t,P=e===void 0;if(K==="value"){let R=ee.defaultValue;ee.removeAttribute(K),ee.defaultValue=R,ee.value=ee.__value=P?R:null}else{let R=ee.defaultChecked;ee.removeAttribute(K),ee.defaultChecked=R,ee.checked=P?R:!1}}else t.removeAttribute(k);else He||A.includes(K)&&(u||typeof I!="string")?(t[K]=I,K in c&&(c[K]=se)):typeof I!="function"&&U(t,K,I)}}}return p&&Je(!0),d}function Ur(t,e,r=[],n=[],i=[],s,a=!1,l=!1){xs(i,r,n,c=>{var u=void 0,f={},p=t.nodeName===zl,d=!1;if(js(()=>{var g=e(...c.map(o)),A=Wl(t,u,g,s,a,l);d&&p&&"value"in g&&$n(t,g.value);for(let N of Object.getOwnPropertySymbols(f))g[N]||de(f[N]);for(let N of Object.getOwnPropertySymbols(g)){var S=g[N];N.description===Ra&&(!u||S!==u[N])&&(f[N]&&de(f[N]),f[N]=Be(()=>Nl(t,()=>S))),A[N]=S}u=A}),p){var m=t;qn(()=>{$n(m,u.value,!0),Vl(m)})}d=!0})}function Zn(t){return t[os]??={[to]:t.nodeName.includes("-"),[ro]:t.namespaceURI===hs}}var es=new Map;function no(t){var e=t.getAttribute("is")||t.nodeName,r=es.get(e);if(r)return r;es.set(e,r=[]);for(var n,i=t,s=Element.prototype;s!==i;){n=da(i);for(var a in n)n[a].set&&a!=="innerHTML"&&a!=="textContent"&&a!=="innerText"&&r.push(a);i=is(i)}return r}function Gl(t,e,r=e){var n=new WeakSet;sl(t,"input",async i=>{var s=i?t.defaultValue:t.value;if(s=dn(t)?pn(s):s,r(s),M!==null&&n.add(M),await xt(),s!==(s=e())){var a=t.selectionStart,l=t.selectionEnd,c=t.value.length;if(t.value=s??"",l!==null){var u=t.value.length;a===l&&l===c&&u>c?(t.selectionStart=u,t.selectionEnd=u):(t.selectionStart=a,t.selectionEnd=Math.min(l,u))}}}),($&&t.defaultValue!==t.value||dr(e)==null&&t.value)&&(r(dn(t)?pn(t.value):t.value),M!==null&&n.add(M)),Pr(()=>{var i=e();if(t===document.activeElement){var s=M;if(n.has(s))return}dn(t)&&i===pn(t.value)||t.type==="date"&&!i&&!t.value||i!==t.value&&(t.value=i??"")})}function dn(t){var e=t.type;return e==="number"||e==="range"}function pn(t){return t===""?null:+t}function vn(t,e){return t===e||t?.[Vt]===e}function mt(t={},e,r,n){var i=Ee.r,s=T;return qn(()=>{var a,l;return Pr(()=>{a=l,l=[],dr(()=>{vn(r(...l),t)||(e(t,...l),a&&vn(r(...a),t)&&e(null,...a))})}),()=>{let c=s;for(;c!==i&&c.parent!==null&&c.parent.f&mn;)c=c.parent;let u=()=>{l&&vn(r(...l),t)&&e(null,...l)},f=c.teardown;c.teardown=()=>{u(),f?.()}}}),t}var Jl={get(t,e){if(!t.exclude.includes(e))return t.props[e]},set(t,e){return!1},getOwnPropertyDescriptor(t,e){if(!t.exclude.includes(e)&&e in t.props)return{enumerable:!0,configurable:!0,value:t.props[e]}},has(t,e){return t.exclude.includes(e)?!1:e in t.props},ownKeys(t){return Reflect.ownKeys(t.props).filter(e=>!t.exclude.includes(e))}};function Kr(t,e,r){return new Proxy({props:t,exclude:e},Jl)}function J(t,e,r,n){var i=n,s=!0,a=()=>(s&&(s=!1,i=n),i),l;l=t[e],l===void 0&&n!==void 0&&(l=a());var c;c=()=>{var d=t[e];return d===void 0?a():(s=!0,d)};var u=!1,f=Kn(()=>(u=!1,c())),p=T;return(function(d,m){if(arguments.length>0){let g=m?o(f):d;return _(f,g),u=!0,i!==void 0&&(i=g),d}return ot&&u||(p.f&Ue)!==0?f.v:o(f)})}function Zl(t){return new Mn(t)}var Mn=class{#e;#t;constructor(e){var r=new Map,n=(s,a)=>{var l=$s(a,!1,!1);return r.set(s,l),l};let i=new Proxy({...e.props||{},$$events:{}},{get(s,a){return o(r.get(a)??n(a,Reflect.get(s,a)))},has(s,a){return a===ya?!0:(o(r.get(a)??n(a,Reflect.get(s,a))),Reflect.has(s,a))},set(s,a,l){return _(r.get(a)??n(a,l),l),Reflect.set(s,a,l)}});this.#t=(e.hydrate?Ol:Zs)(e.component,{target:e.target,anchor:e.anchor,props:i,context:e.context,intro:e.intro??!1,recover:e.recover,transformError:e.transformError}),(!e?.props?.$$host||e.sync===!1)&&q(),this.#e=i.$$events;for(let s of Object.keys(this.#t))s==="$set"||s==="$destroy"||s==="$on"||sr(this,s,{get(){return this.#t[s]},set(a){this.#t[s]=a},enumerable:!0});this.#t.$set=s=>{Object.assign(i,s)},this.#t.$destroy=()=>{Sl(this.#t)}}$set(e){this.#t.$set(e)}$on(e,r){this.#e[e]=this.#e[e]||[];let n=(...i)=>r.call(this,...i);return this.#e[e].push(n),()=>{this.#e[e]=this.#e[e].filter(i=>i!==n)}}$destroy(){this.#t.$destroy()}},io=class{};typeof HTMLElement=="function"&&(io=class extends HTMLElement{$$ctor;$$s;$$c;$$cn=!1;$$d={};$$r=!1;$$p_d={};$$l={};$$l_u=new Map;$$me;$$shadowRoot=null;constructor(t,e,r){super(),this.$$ctor=t,this.$$s=e,r&&(this.$$shadowRoot=this.attachShadow(r))}addEventListener(t,e,r){if(this.$$l[t]=this.$$l[t]||[],this.$$l[t].push(e),this.$$c){let n=this.$$c.$on(t,e);this.$$l_u.set(e,n)}super.addEventListener(t,e,r)}removeEventListener(t,e,r){if(super.removeEventListener(t,e,r),this.$$c){let n=this.$$l_u.get(e);n&&(n(),this.$$l_u.delete(e))}}async connectedCallback(){if(this.$$cn=!0,!this.$$c){let e=function(i){return s=>{let a=Pn("slot");i!=="default"&&(a.name=i),L(s,a)}};var t=e;if(await Promise.resolve(),!this.$$cn||this.$$c)return;let r={},n=Xl(this);for(let i of this.$$s)i in n&&(i==="default"&&!this.$$d.children?(this.$$d.children=e(i),r.default=!0):r[i]=e(i));for(let i of this.attributes){let s=this.$$g_p(i.name);s in this.$$d||(this.$$d[s]=Or(s,i.value,this.$$p_d,"toProp"))}for(let i in this.$$p_d)!(i in this.$$d)&&this[i]!==void 0&&(this.$$d[i]=this[i],delete this[i]);this.$$c=Zl({component:this.$$ctor,target:this.$$shadowRoot||this,props:{...this.$$d,$$slots:r,$$host:this}}),this.$$me=ul(()=>{Pr(()=>{this.$$r=!0;for(let i of Sr(this.$$c)){if(!this.$$p_d[i]?.reflect)continue;this.$$d[i]=this.$$c[i];let s=Or(i,this.$$d[i],this.$$p_d,"toAttribute");s==null?this.removeAttribute(this.$$p_d[i].attribute||i):this.setAttribute(this.$$p_d[i].attribute||i,s)}this.$$r=!1})});for(let i in this.$$l)for(let s of this.$$l[i]){let a=this.$$c.$on(i,s);this.$$l_u.set(s,a)}this.$$l={}}}attributeChangedCallback(t,e,r){this.$$r||(t=this.$$g_p(t),this.$$d[t]=Or(t,r,this.$$p_d,"toProp"),this.$$c?.$set({[t]:this.$$d[t]}))}disconnectedCallback(){this.$$cn=!1,Promise.resolve().then(()=>{!this.$$cn&&this.$$c&&(this.$$c.$destroy(),this.$$me(),this.$$c=void 0)})}$$g_p(t){return Sr(this.$$p_d).find(e=>this.$$p_d[e].attribute===t||!this.$$p_d[e].attribute&&e.toLowerCase()===t)||t}});function Or(t,e,r,n){let i=r[t]?.type;if(e=i==="Boolean"&&typeof e!="boolean"?e!=null:e,!n||!r[t])return e;if(n==="toAttribute")switch(i){case"Object":case"Array":return e==null?null:JSON.stringify(e);case"Boolean":return e?"":null;case"Number":return e??null;default:return e}else switch(i){case"Object":case"Array":return e&&JSON.parse(e);case"Boolean":return e;case"Number":return e!=null?+e:e;default:return e}}function Xl(t){let e={};return t.childNodes.forEach(r=>{e[r.slot||"default"]=!0}),e}function yt(t,e,r,n,i,s){let a=class extends io{constructor(){super(t,r,i),this.$$p_d=e}static get observedAttributes(){return Sr(e).map(l=>(e[l].attribute||l).toLowerCase())}};return Sr(e).forEach(l=>{sr(a.prototype,l,{get(){return this.$$c&&l in this.$$c?this.$$c[l]:this.$$d[l]},set(c){c=Or(l,c,e),this.$$d[l]=c;var u=this.$$c;if(u){var f=Bt(u,l)?.get;f?u[l]=c:u.$set({[l]:c})}}})}),n.forEach(l=>{sr(a.prototype,l,{get(){return this.$$c?.[l]}})}),t.element=a,a}var Ql=Z('<div class="altcha-checkbox"><input/> <svg aria-hidden="true" width="12" height="9" viewBox="0 0 12 9"><polyline points="1 5 4 8 11 1"></polyline></svg> <div class="altcha-spinner altcha-checkbox-spinner" aria-hidden="true"></div></div>');function so(t,e){at(e,!0);let r=J(e,"loading"),n=Kr(e,["$$slots","$$events","$$legacy","$$host","loading"]),i;function s(){i?.click()}var a={get loading(){return r()},set loading(f){r(f),q()}},l=Ql(),c=Q(l);Ur(c,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),mt(c,f=>i=f,()=>i);var u=G(c,2);return Rn(2),W(l),ve(()=>U(l,"data-loading",r())),Vr("click",u,s),L(t,l),lt(a)}jr(["click"]);yt(so,{loading:{}},[],[],{mode:"open"});var ec=Z('<div class="altcha-checkbox-native"><input/> <div class="altcha-spinner altcha-checkbox-native-spinner"></div></div>');function oo(t,e){at(e,!0);let r=J(e,"loading"),n=Kr(e,["$$slots","$$events","$$legacy","$$host","loading"]);var i={get loading(){return r()},set loading(l){r(l),q()}},s=ec(),a=Q(s);return Ur(a,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),Rn(2),W(s),ve(()=>U(s,"data-loading",r())),L(t,s),lt(i)}yt(oo,{loading:{}},[],[],{mode:"open"});var tc=Z('<div><a target="_blank" class="altcha-logo" aria-hidden="true" tabindex="-1"><svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.33955 16.4279C5.88954 20.6586 12.1971 21.2105 16.4279 17.6604C18.4699 15.947 19.6548 13.5911 19.9352 11.1365L17.9886 10.4279C17.8738 12.5624 16.909 14.6459 15.1423 16.1284C11.7577 18.9684 6.71167 18.5269 3.87164 15.1423C1.03163 11.7577 1.4731 6.71166 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577C16.9767 5.86872 17.5322 7.02798 17.804 8.2324L19.9522 9.01429C19.7622 7.07737 19.0059 5.17558 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956C-0.658625 5.88958 -1.21046 12.1971 2.33955 16.4279Z" fill="currentColor"></path><path d="M3.57212 2.33956C1.65755 3.94607 0.496389 6.11731 0.12782 8.40523L2.04639 9.13961C2.26047 7.15832 3.21057 5.25375 4.8577 3.87164C8.24231 1.03162 13.2883 1.4731 16.1284 4.8577L13.8302 6.78606L19.9633 9.13364C19.7929 7.15555 19.0335 5.20847 17.6604 3.57212C14.1104 -0.658624 7.80283 -1.21043 3.57212 2.33956Z" fill="currentColor"></path><path d="M7 10H5C5 12.7614 7.23858 15 10 15C12.7614 15 15 12.7614 15 10H13C13 11.6569 11.6569 13 10 13C8.3431 13 7 11.6569 7 10Z" fill="currentColor"></path></svg></a></div>');function Xn(t,e){at(e,!0);let r=J(e,"strings"),n="https://altcha.org";var i={get strings(){return r()},set strings(l){r(l),q()}},s=tc(),a=Q(s);return U(a,"href",n),W(s),ve(()=>U(a,"aria-label",r().ariaLinkLabel)),L(t,s),lt(i)}yt(Xn,{strings:{}},[],[],{mode:"open"});var rc=Z('<div class="altcha-footer"><p></p> <!></div>');function Fn(t,e){at(e,!0);let r=J(e,"logo"),n=J(e,"strings");var i={get logo(){return r()},set logo(u){r(u),q()},get strings(){return n()},set strings(u){n(u),q()}},s=rc(),a=Q(s);Qs(a,()=>n().footer,!0),W(a);var l=G(a,2);{var c=u=>{Xn(u,{get strings(){return n()}})};le(l,u=>{r()&&u(c)})}return W(s),L(t,s),lt(i)}yt(Fn,{logo:{},strings:{}},[],[],{mode:"open"});var nc=Z('<div class="altcha-switch"><input/>  <div class="altcha-switch-toggle"><div class="altcha-spinner altcha-switch-spinner"></div></div></div>');function ao(t,e){at(e,!0);let r=J(e,"loading"),n=Kr(e,["$$slots","$$events","$$legacy","$$host","loading"]),i;function s(){i?.click()}var a={get loading(){return r()},set loading(f){r(f),q()}},l=nc(),c=Q(l);Ur(c,()=>({type:"checkbox",...n}),void 0,void 0,void 0,void 0,!0),mt(c,f=>i=f,()=>i);var u=G(c,2);return W(l),ve(()=>U(l,"data-loading",r())),Vr("click",u,s),L(t,l),lt(a)}jr(["click"]);yt(ao,{loading:{}},[],[],{mode:"open"});var he=(t=>(t.ERROR="error",t.LOADING="loading",t.PLAYING="playing",t.PAUSED="paused",t.READY="ready",t))(he||{}),V=(t=>(t.CODE="code",t.ERROR="error",t.VERIFIED="verified",t.VERIFYING="verifying",t.UNVERIFIED="unverified",t.EXPIRED="expired",t))(V||{}),ic=Z('<div class="altcha-code-challenge-title"> </div>'),sc=Z('<div class="altcha-spinner"></div>'),oc=Wn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.8659 3.00017L22.3922 19.5002C22.6684 19.9785 22.5045 20.5901 22.0262 20.8662C21.8742 20.954 21.7017 21.0002 21.5262 21.0002H2.47363C1.92135 21.0002 1.47363 20.5525 1.47363 20.0002C1.47363 19.8246 1.51984 19.6522 1.60761 19.5002L11.1339 3.00017C11.41 2.52187 12.0216 2.358 12.4999 2.63414C12.6519 2.72191 12.7782 2.84815 12.8659 3.00017ZM10.9999 16.0002V18.0002H12.9999V16.0002H10.9999ZM10.9999 9.00017V14.0002H12.9999V9.00017H10.9999Z"></path></svg>'),ac=Wn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15 7C15 6.44772 15.4477 6 16 6C16.5523 6 17 6.44772 17 7V17C17 17.5523 16.5523 18 16 18C15.4477 18 15 17.5523 15 17V7ZM7 7C7 6.44772 7.44772 6 8 6C8.55228 6 9 6.44772 9 7V17C9 17.5523 8.55228 18 8 18C7.44772 18 7 17.5523 7 17V7Z"></path></svg>'),lc=Wn('<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M4 12H7C8.10457 12 9 12.8954 9 14V19C9 20.1046 8.10457 21 7 21H4C2.89543 21 2 20.1046 2 19V12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12V19C22 20.1046 21.1046 21 20 21H17C15.8954 21 15 20.1046 15 19V14C15 12.8954 15.8954 12 17 12H20C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12Z"></path></svg>'),cc=Z('<button type="button" class="altcha-button altcha-button-secondary"><!></button>'),uc=Z('<audio hidden="" autoplay=""></audio>'),hc=Z('<div class="altcha-code-challenge"><form data-code-challenge="true"><!> <div class="altcha-code-challenge-text"> </div> <img class="altcha-code-challenge-image" alt=""/> <div class="altcha-code-challenge-row"><input type="text" class="altcha-input" autocomplete="off" name="" required=""/> <!> <button type="button" class="altcha-button altcha-button-secondary"><svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2V4C16.4183 4 20 7.58172 20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 9.25022 5.38734 6.82447 7.50024 5.38451L7.5 8H9.5V2L3.5 2V4L5.99918 3.99989C3.57075 5.82434 2 8.72873 2 12Z"></path></svg></button></div> <div class="altcha-code-challenge-buttons"><button type="submit" class="altcha-button"> </button> <button type="button" class="altcha-button altcha-button-secondary"> </button></div></form> <!></div>');function lo(t,e){at(e,!0);let r=J(e,"audioUrl"),n=J(e,"codeChallenge"),i=J(e,"config"),s=J(e,"imageUrl"),a=J(e,"onCancel"),l=J(e,"onReload"),c=J(e,"onSubmit"),u=J(e,"strings"),f=F(void 0),p=F(void 0),d=F(void 0),m=F(!1),g=F(""),A=F(!1);Gn(()=>(i().disableAutoFocus||xt().then(()=>{o(d)?.focus()}),()=>{o(p)&&(o(p).pause(),_(p,void 0))}));function S(){_(f,he.PAUSED,!0)}function N(y){_(f,he.ERROR,!0)}function oe(){_(f,he.READY,!0)}function ne(){_(f,he.LOADING,!0)}function ae(){_(f,he.PLAYING,!0)}function K(){_(f,he.PAUSED,!0)}function He(y){y.code==="Space"?(y.preventDefault(),y.stopPropagation(),I()):y.code==="Escape"&&(y.preventDefault(),y.stopPropagation(),a()?.())}function k(y){y.preventDefault(),y.stopPropagation(),c()?.(o(g))}function I(){o(p)?o(f)===he.LOADING||(o(p).paused?(r()&&o(p).src!==r()&&(o(p).src=r()),o(p).currentTime=0,o(p).play()):o(p).pause()):(_(A,!0),requestAnimationFrame(()=>{o(p)&&r()&&(o(p).src=r(),o(p).play())}))}var ee={get audioUrl(){return r()},set audioUrl(y){r(y),q()},get codeChallenge(){return n()},set codeChallenge(y){n(y),q()},get config(){return i()},set config(y){i(y),q()},get imageUrl(){return s()},set imageUrl(y){s(y),q()},get onCancel(){return a()},set onCancel(y){a(y),q()},get onReload(){return l()},set onReload(y){l(y),q()},get onSubmit(){return c()},set onSubmit(y){c(y),q()},get strings(){return u()},set strings(y){u(y),q()}},P=hc(),R=Q(P),Re=Q(R);{var ge=y=>{var te=ic(),Et=Q(te,!0);W(te),ve(()=>Ge(Et,u().verificationRequired)),L(y,te)};le(Re,y=>{i().codeChallengeDisplay!=="standard"&&y(ge)})}var ye=G(Re,2),X=Q(ye,!0);W(ye);var qe=G(ye,2),x=G(qe,2),z=Q(x);Jn(z),z.disabled=o(m),mt(z,y=>_(d,y),()=>o(d));var ke=G(z,2);{var b=y=>{var te=cc(),Et=Q(te);{var Xr=we=>{var Ye=sc();L(we,Ye)},Gt=we=>{var Ye=oc();L(we,Ye)},Qr=we=>{var Ye=ac();L(we,Ye)},en=we=>{var Ye=lc();L(we,Ye)};le(Et,we=>{o(f)===he.LOADING?we(Xr):o(f)===he.ERROR?we(Gt,1):o(f)===he.PLAYING?we(Qr,2):we(en,-1)})}W(te),ve(()=>{U(te,"title",u().getAudioChallenge),te.disabled=o(f)===he.LOADING||o(f)===he.ERROR,U(te,"aria-label",o(f)===he.LOADING?u().loading:u().getAudioChallenge)}),ce("click",te,()=>I(),!0),L(y,te)};le(ke,y=>{n().audio&&y(b)})}var wt=G(ke,2);W(x);var br=G(x,2),Te=Q(br),Zr=Q(Te,!0);W(Te);var _t=G(Te,2),qt=Q(_t,!0);W(_t),W(br),W(R);var Yt=G(R,2);{var Wt=y=>{var te=uc();mt(te,Et=>_(p,Et),()=>o(p)),ce("error",te,N),ce("loadstart",te,ne),ce("canplay",te,oe),ce("pause",te,K),ce("playing",te,ae),ce("ended",te,S),L(y,te)};le(Yt,y=>{o(A)&&y(Wt)})}return W(P),ve(()=>{Ge(X,u().enterCodeFromImage),U(qe,"src",s()),U(z,"minlength",n().length||1),U(z,"maxlength",n().length),U(z,"placeholder",u().enterCode),U(z,"aria-label",o(f)===he.LOADING?u().loading:o(f)===he.PLAYING?"":u().enterCodeAria),U(z,"aria-live",o(f)?"assertive":"polite"),U(z,"aria-busy",o(f)===he.LOADING),U(wt,"title",u().reload),U(wt,"aria-label",u().reload),U(Te,"aria-label",u().verify),Ge(Zr,u().verify),U(_t,"aria-label",u().cancel),Ge(qt,u().cancel)}),ce("submit",R,k,!0),Vr("keydown",z,He),Gl(z,()=>o(g),y=>_(g,y)),ce("click",wt,()=>l()?.(),!0),ce("click",_t,()=>a()?.(),!0),L(t,P),lt(ee)}jr(["keydown"]);yt(lo,{audioUrl:{},codeChallenge:{},config:{},imageUrl:{},onCancel:{},onReload:{},onSubmit:{},strings:{}},[],[],{mode:"open"});var fc=Z('<div class="altcha-popover-backdrop" data-backdrop=""></div>'),dc=Z('<div class="altcha-popover-arrow"></div>'),pc=Z('<div role="button" class="altcha-popover-close">&times;</div>'),vc=Z('<!> <div><!> <!> <div class="altcha-popover-content"><!></div></div>',1);function Nn(t,e){at(e,!0);let r=J(e,"anchor"),n=J(e,"children"),i=J(e,"display",7,"standard"),s=J(e,"backdrop",7,!1),a=J(e,"onClickOutside"),l=J(e,"onClickOutsideDelay",7,600),c=J(e,"onClose"),u=J(e,"placement",7,"auto"),f=J(e,"updateUISignal"),p=J(e,"variant",7,"neutral"),d=Kr(e,["$$slots","$$events","$$legacy","$$host","anchor","children","display","backdrop","onClickOutside","onClickOutsideDelay","onClose","placement","updateUISignal","variant"]),m=F(void 0),g=F(void 0),A=F(!1),S=F(0);Ce(()=>{u()!=="auto"&&_(A,u()==="top")}),Ce(()=>{f()&&K()}),Gn(()=>{let x=i()==="bottomsheet"||i()==="overlay";return x&&(o(g)&&document.body.append(o(g)),o(m)&&document.body.append(o(m))),K(),xt().then(()=>{_(S,Date.now(),!0)}),()=>{x&&(o(g)&&document.body.removeChild(o(g)),o(m)&&document.body.removeChild(o(m)))}});function N(){c()?.()}function oe(x){let z=x.target;!o(m)?.contains(z)&&(!l()||o(S)+l()<Date.now())&&a()?.()}function ne(){K()}function ae(){K()}function K(){if(r()&&u()==="auto"&&o(m)){let x=r().getBoundingClientRect(),ke=document.documentElement.clientHeight-(x.top+x.height)<o(m).clientHeight;o(A)!==ke&&_(A,ke)}}var He={get anchor(){return r()},set anchor(x){r(x),q()},get children(){return n()},set children(x){n(x),q()},get display(){return i()},set display(x="standard"){i(x),q()},get backdrop(){return s()},set backdrop(x=!1){s(x),q()},get onClickOutside(){return a()},set onClickOutside(x){a(x),q()},get onClickOutsideDelay(){return l()},set onClickOutsideDelay(x=600){l(x),q()},get onClose(){return c()},set onClose(x){c(x),q()},get placement(){return u()},set placement(x="auto"){u(x),q()},get updateUISignal(){return f()},set updateUISignal(x){f(x),q()},get variant(){return p()},set variant(x="neutral"){p(x),q()}},k=vc();ce("click",Ct,oe,!0),ce("resize",Ct,ne),ce("scroll",Ct,ae);var I=Dt(k);{var ee=x=>{var z=fc();mt(z,ke=>_(g,ke),()=>o(g)),L(x,z)};le(I,x=>{s()&&x(ee)})}var P=G(I,2);Ur(P,()=>({...d,class:`altcha-popover ${(e.class||"")??""}`,"data-popover":!0,"data-variant":p(),"data-top":o(A),"data-display":i()}));var R=Q(P);{var Re=x=>{var z=dc();L(x,z)};le(R,x=>{i()==="standard"&&x(Re)})}var ge=G(R,2);{var ye=x=>{var z=pc();ce("click",z,N,!0),L(x,z)};le(ge,x=>{i()!=="standard"&&x(ye)})}var X=G(ge,2),qe=Q(X);return Tl(qe,()=>n()??dt),W(X),W(P),mt(P,x=>_(m,x),()=>o(m)),L(t,k),lt(He)}yt(Nn,{anchor:{},children:{},display:{},backdrop:{},onClickOutside:{},onClickOutsideDelay:{},onClose:{},placement:{},updateUISignal:{},variant:{}},[],[],{mode:"open"});function gc(t){return Array.from(new Uint8Array(t)).map(e=>e.toString(16).padStart(2,"0")).join("")}function mc(t,e="altcha-css",r){if(typeof document<"u"&&document&&!document.getElementById(e)){let n=document.createElement("style");n.id=e,n.textContent=t;let i=document.currentScript?.nonce??document.querySelector('meta[name="csp-nonce"]')?.content;i&&(n.nonce=i),document.head.appendChild(n)}}async function co(t){let{challenge:e,concurrency:r=navigator.hardwareConcurrency,controller:n=new AbortController,createWorker:i,onOutOfMemory:s=d=>d>1?Math.floor(d/2):0,counterMode:a,timeout:l=9e4}=t,c=Math.min(16,Math.max(1,r)),u=[],f=()=>{for(let d of u)d.terminate()};for(let d=0;d<c;d++)u.push(await i(e.parameters.algorithm));let p=null;try{p=await Promise.race(u.map((d,m)=>(n.signal.addEventListener("abort",()=>{d.postMessage({type:"abort"})}),new Promise((g,A)=>{d.addEventListener("error",S=>{A(S)}),d.addEventListener("message",S=>{if(S.data){for(let N of u)N!==d&&N.postMessage({type:"abort"});if(S.data.error)return A(new Error(S.data.error))}g(S.data)}),d.postMessage({challenge:e,counterMode:a,counterStart:m,counterStep:c,timeout:l,type:"work"})}))))}catch(d){if(d instanceof Error&&!!d?.message?.includes("Out of memory")&&s){f();let g=s(c);if(g)return co({...t,challenge:e,controller:n,concurrency:g,createWorker:i})}throw d}finally{f()}return n.signal.aborted?null:p||null}var In=class{TAG_CODES={INPUT:1,TEXTAREA:2,SELECT:3,BUTTON:4,A:5,DETAILS:6,SUMMARY:7,IFRAME:8,VIDEO:9,AUDIO:10};maxSamples;sampleInterval;target;focusStartTime=0;focusInteraction=0;focusInteractionTimer=null;lastPointerSample=0;lastTouchSample=0;lastScrollSample=0;pendingPointer=null;pendingTouch=null;focus=[];pointer=[];scroll=[];touch=[];constructor(e={}){let{maxSamples:r=60,sampleInterval:n=50,target:i=window}=e;this.maxSamples=r,this.sampleInterval=n,this.target=i,this.attach()}destroy(){let e={capture:!0};this.target.removeEventListener("focusin",this.onFocus,e),this.target.removeEventListener("keydown",this.onInteraction,e),this.target.removeEventListener("pointerdown",this.onInteraction,e),this.target.removeEventListener("pointermove",this.onPointer,e),this.target.removeEventListener("scroll",this.onScroll,e),this.target.removeEventListener("touchmove",this.onTouchMove,e)}export(){return{focus:this.focus,maxTouchPoints:navigator.maxTouchPoints||0,pointer:this.pointer,scroll:this.scroll,time:Date.now(),touch:this.touch}}attach(){let e={passive:!0,capture:!0};this.target.addEventListener("focusin",this.onFocus,e),this.target.addEventListener("keydown",this.onInteraction,e),this.target.addEventListener("pointerdown",this.onInteraction,e),this.target.addEventListener("pointermove",this.onPointer,e),this.target.addEventListener("scroll",this.onScroll,e),this.target.addEventListener("touchmove",this.onTouchMove,e)}evict(e){e.length>this.maxSamples&&e.splice(0,e.length-this.maxSamples)}onFocus=e=>{if(this.focusInteraction===2)return;let r=e.target;if(!(r instanceof Element))return;let n=performance.now();this.focusStartTime===0&&(this.focusStartTime=n),this.focus.push([Math.round(n-this.focusStartTime),r.tabIndex,this.TAG_CODES[r.tagName]??0,this.focusInteraction?1:0]),this.evict(this.focus)};onInteraction=e=>{this.focusInteraction="keyCode"in e?1:2,this.focusInteractionTimer&&clearTimeout(this.focusInteractionTimer),this.focusInteractionTimer=setTimeout(()=>{this.focusInteraction=0},100)};onPointer=e=>{if(e.pointerType==="touch")return;let r=e.timeStamp||performance.now();this.pendingPointer=[Math.round(e.clientX),Math.round(e.clientY),Math.round(r)],r-this.lastPointerSample>=this.sampleInterval&&(this.pointer.push(this.pendingPointer),this.lastPointerSample=r,this.pendingPointer=null,this.evict(this.pointer))};onScroll=()=>{let e=performance.now();e-this.lastScrollSample<this.sampleInterval||(this.scroll.push([Math.round(window.scrollY),Math.round(e)]),this.lastScrollSample=e,this.evict(this.scroll))};onTouchMove=e=>{let r=e.timeStamp||performance.now(),n=e.touches[0];n&&(this.pendingTouch=[Math.round(n.clientX),Math.round(n.clientY),Math.round(r),Math.round(n.force*1e3)/1e3,Math.round(n.radiusX||0),Math.round(n.radiusY||0)],r-this.lastTouchSample>=this.sampleInterval&&(this.touch.push(this.pendingTouch),this.lastTouchSample=r,this.pendingTouch=null,this.evict(this.touch)))}},bc=Z('<div class="altcha-overlay-backdrop" data-backdrop=""></div>'),yc=Z('<div class="altcha-overlay-content"></div>'),wc=Z('<div role="button" class="altcha-overlay-close">&times;</div> <!>',1),_c=Z('<div class="altcha-floating-arrow"></div>'),Ec=Z('<input type="hidden"/>'),kc=Z('<div class="altcha-error">Secure context (HTTPS) required.</div>'),Ac=Z('<div class="altcha-error"> </div>'),xc=Z('<div class="altcha-error"> </div>'),Cc=Z("<!> <!>",1),Oc=Z('<!> <div class="altcha"><!> <div class="altcha-main"><div><div class="altcha-checkbox-wrap"><!> <label><!></label></div> <!></div> <!> <!> <!></div> <!></div>',1);function Sc(t,e){at(e,!0);let r=()=>Ui(f,"$altchaDefaults",i),n=()=>Ui(g,"$altchaI18nStore",i),[i,s]=Ya(),a='input[type="text"]:not([data-no-spamfilter]), textarea:not([data-no-spamfilter])',l='input[type="submit"], button[type="submit"], button:not([type="button"]):not([type="reset"])',c=["ar","fa","he","ur"],{isSecureContext:u}=globalThis,{store:f}=globalThis.$altcha.defaults,p=navigator.hardwareConcurrency||2,d=navigator.deviceMemory||0,m=d&&d<=4?Math.min(4,p):p,g=globalThis.$altcha.i18n.store,A=e.$$host,S=(h,v)=>{xt().then(()=>{A?.dispatchEvent(new CustomEvent(h,{detail:v}))})},N=null,oe=F(nt(new URL(location.origin))),ne=F(!1),ae=F(null),K=F(null),He=F(null),k=F(nt(V.UNVERIFIED)),I=F(void 0),ee=F(void 0),P=F(null),R=F(void 0),Re=F(null),ge=F(null),ye=F(null),X=F(null),qe=F(nt([])),x=F(0),z=F(nt({})),ke=F(!0),b=me(()=>({fetch:(h,v)=>fetch(h,v),audioChallengeLanguage:"",auto:"off",barPlacement:"bottom",challenge:"",codeChallenge:null,codeChallengeDisplay:"standard",credentials:null,debug:!1,disableAutoFocus:!1,display:"standard",floatingAnchor:"",floatingOffset:8,floatingPersist:!1,floatingPlacement:"auto",hideFooter:!1,hideLogo:!1,humanInteractionSignature:!0,language:"",mockError:!1,minDuration:500,overlayContent:"",name:"altcha",popoverPlacement:"auto",retryOnOutOfMemoryError:!0,setCookie:null,serverVerificationFields:!1,serverVerificationTimeZone:!1,test:!1,timeout:9e4,type:"checkbox",validationMessage:"",verifyFunction:null,verifyUrl:"",workers:m,...r(),...o(z)})),wt=me(()=>`altcha-checkbox-${e.id||Math.floor(Math.random()*1e12).toString(16)}`),br=me(()=>So(o(b).type)),Te=me(()=>o(b).auto),Zr=me(()=>o(k)===V.VERIFYING),_t=me(()=>!o(b).hideFooter),qt=me(()=>!o(b).hideLogo&&o(b).display!=="bar"),Yt=me(()=>To(n(),[o(b).language,document.documentElement.lang,...navigator.languages])),Wt=me(()=>c.includes(o(Yt).language)?"rtl":void 0),y=me(()=>({...o(Yt).strings})),te=me(()=>o(ae)?.audio?.match(/^(https?:)?\//)?yr(o(ae).audio,o(oe),{language:o(b).audioChallengeLanguage||o(Yt).language}).toString():o(ae)?.audio),Et=me(()=>o(ae)?.image?.match(/^(https?:)?\//)?yr(o(ae).image,o(oe)):o(ae)?.image);Ce(()=>{Jt({auto:e.auto,challenge:e.challenge,display:e.display,language:e.language,name:e.name,type:e.type,workers:e.workers})}),Ce(()=>{if(e.configuration)try{Jt(JSON.parse(e.configuration))}catch{H("unable to parse the `configuration` attribute (JSON expected)")}}),Ce(()=>{o(He)!==o(b).display&&wr(o(b).display)}),Ce(()=>{o(ne)&&o(k)===V.VERIFYING&&_(ne,!1)}),Ce(()=>{!o(ne)&&o(k)===V.VERIFIED&&_(ne,!0)}),Ce(()=>{if(!o(ne)){let h=tn();h&&h.checked&&(h.checked=!1)}}),Ce(()=>{o(k)===V.VERIFIED&&tn()?.setCustomValidity("")}),Ce(()=>{if(o(Te)==="onload"){let h=setTimeout(()=>{Nt()},1);return()=>{h&&clearTimeout(h)}}}),Ce(()=>{o(ge)&&H("error:",o(ge))}),Ce(()=>{o(X)&&o(b).setCookie&&Ko(o(X),o(b).setCookie)}),Gn(()=>(H("mounted","3.0.10"),A&&globalThis.$altcha.instances.add(A),_(P,o(R)?.closest("form"),!0),o(P)?.addEventListener("reset",Si),o(P)?.addEventListener("submit",Ti,{capture:!0}),o(P)?.addEventListener("focusin",Oi),Xr(),o(b).humanInteractionSignature&&(H("human interaction signature enabled"),N=new In),S("load"),u||H("secure context (HTTPS) required"),()=>{Qr(),A&&globalThis.$altcha.instances.delete(A),o(ye)&&clearTimeout(o(ye)),o(P)?.removeEventListener("reset",Si),o(P)?.removeEventListener("submit",Ti,{capture:!0}),o(P)?.removeEventListener("focusin",Oi),N?.destroy()}));function Xr(){_(qe,[...globalThis.$altcha.plugins].map(h=>new h(A)),!0),H("activating plugins",o(qe).map(h=>h.constructor.name));for(let h of o(qe))h.activate()}async function Gt(h,...v){let w;for(let E of o(qe))w=await E[h].call(E,...v);return w}function Qr(){for(let h of o(qe))h.destroy()}function en(h){let[v,w]=h.salt.split("?"),E={};if(w)try{Object.assign(E,Object.fromEntries(new URLSearchParams(w).entries()))}catch{}let O={codeChallenge:h.codeChallenge,parameters:{algorithm:h.algorithm,cost:1,data:E,expiresAt:E?.expires?parseInt(E.expires,10):void 0,keyLength:h.algorithm==="SHA-512"?64:h.algorithm==="SHA-384"?48:32,nonce:gc(new TextEncoder().encode(h.salt)),keyPrefix:h.challenge,salt:""},signature:h.signature};return Object.defineProperties(O,{_originalSalt:{enumerable:!1,value:h.salt,writable:!1},_version:{enumerable:!1,value:1,writable:!1}}),O}function we(h,v){return{algorithm:h.parameters.algorithm,challenge:h.parameters.keyPrefix,number:v.counter,salt:"_originalSalt"in h?h._originalSalt:h.parameters.nonce,signature:h.signature,took:v.time||0}}async function Ye(h){await new Promise(v=>setTimeout(v,h))}async function Ci(h=o(b).challenge,v){let w=await Gt("onFetchChallenge",h),E=null;if(w!==void 0)return w;if(typeof h=="string")if(h.startsWith("{")){H("parsing JSON challenge");try{E=JSON.parse(h)}catch{throw new Error("Unable to parse JSON challenge.")}}else{H("fetching challenge from",v?.method||"GET",h),_(oe,new URL(h,location.origin),!0);let O=await o(b).fetch(h,{credentials:o(b).credentials||void 0,...v});await Mi(O);let C=O.headers.get("x-altcha-config");C&&Vo(C);let j=await O.json();if(j&&"his"in j&&j.his){if(H("requested HIS"),!N)throw new Error("Server requested HIS data but collector is disabled.");return Ci(yr(j.his.url,o(oe)),{body:JSON.stringify({his:N.export()}),headers:{"content-type":"application/json"},method:"POST"})}j&&"hisResult"in j&&j.hisResult&&H("HIS result",j.hisResult),E=j}else if(h&&typeof h=="object")try{E=JSON.parse(JSON.stringify(h))}catch{throw new Error("Unable to parse JSON challenge.")}if(Co(E)&&(E=en(E)),!Oo(E))throw new Error("Challenge validation failed.");return E}function Co(h){return typeof h=="object"&&"challenge"in h}function Oo(h){return!!h&&typeof h=="object"&&"parameters"in h&&!!h.parameters&&typeof h.parameters=="object"&&"algorithm"in h.parameters&&"nonce"in h.parameters&&"salt"in h.parameters&&"keyPrefix"in h.parameters}function tn(){return document.getElementById(o(wt))}function So(h){switch(h){case"checkbox":return so;case"switch":return ao;default:return oo}}function To(h,v){let w=Object.keys(h).map(O=>O.toLowerCase()),E=v.reduce((O,C)=>(C=C.toLowerCase(),O||(h[C]?C:null)||w.find(j=>C.split("-")[0]===j.split("-")[0])||null),null);return h[E||""]||(E="en"),{language:E,strings:h[E]}}function $o(h){switch(h){case"bar":return o(b).barPlacement||"bottom";case"floating":return o(b).floatingPlacement||"auto";default:return}}function Mo(h){return[...o(P)?.querySelectorAll(a)||[]].reduce((w,E)=>{let O=E.name,C=E.value;return O&&C&&(w[O]=/\n/.test(C)?C.replace(new RegExp("(?<!\\r)\\n","g"),`\r
`):C),w},{})}function Fo(){try{return Intl.DateTimeFormat().resolvedOptions().timeZone}catch{}}function yr(h,v,w){let E=new URL(h,v);if(E.search||(E.search=v.search),w)for(let O in w)w[O]!==void 0&&w[O]!==null&&E.searchParams.set(O,w[O]);return E.toString()}function No(h){!o(ne)&&h.currentTarget.checked?(h.preventDefault(),h.currentTarget.checked=!1,o(k)!==V.VERIFYING&&Nt()):h.currentTarget.checked||(h.preventDefault(),$e())}function Io(h){o(k)===V.VERIFYING?h.currentTarget.setCustomValidity(o(y).waitAlert):o(b).validationMessage&&h.currentTarget.setCustomValidity(o(b).validationMessage)}function Lo(){wr(o(b).display),$e()}function Do(){_r()}function Ro(h){let v=h.target;o(b).display==="floating"&&v&&!A?.contains(v)&&!v.hasAttribute("data-backdrop")&&!v.closest("[data-popover]")&&o(k)!==V.VERIFIED&&!o(b).floatingPersist&&rn()}function Oi(h){o(Te)==="onfocus"&&o(k)===V.UNVERIFIED&&Nt()}function Si(){wr(o(b).display),$e()}function Ti(h){h.target?.getAttribute("data-code-challenge")!=="true"&&o(Te)==="onsubmit"&&o(k)===V.UNVERIFIED&&(h.preventDefault(),h.stopPropagation(),_(Re,h.submitter,!0),nn(),Nt().then(w=>{w&&!o(ae)&&xt().then(()=>{$i(o(Re))})}))}function Bo(h){h.persisted&&(wr(o(b).display),$e())}function Po(){_r()}function Vo(h){try{let v=JSON.parse(h);v&&typeof v=="object"&&Jt({serverVerificationFields:v?.sentinel?.fields,serverVerificationTimeZone:v?.sentinel?.timeZone,verifyUrl:v.verifyurl,...v})}catch(v){H("unable to configure from x-altcha-config header",v)}}function jo(h=20){if(!o(R))return;let v=o(b).floatingPlacement;if(!o(ee)&&(_(ee,(o(b).floatingAnchor instanceof HTMLElement?o(b).floatingAnchor:o(b).floatingAnchor?document.querySelector(o(b).floatingAnchor):o(P)?.querySelector(l))||o(P),!0),!o(ee))){H("unable to find floating anchor element");return}let w=parseInt(o(b).floatingOffset,10)||12,E=o(ee).getBoundingClientRect(),O=o(R).getBoundingClientRect(),C=document.documentElement.clientHeight,j=document.documentElement.clientWidth,Ae=!v||v==="auto"?E.bottom+O.height+w+h>C:v==="top",Y=Math.max(h,Math.min(j-h-O.width,E.left+E.width/2-O.width/2));if(o(R).style.setProperty("--altcha-floating-left",`${Y}px`),o(R).style.setProperty("--altcha-floating-top",Ae?`${E.top-(O.height+w)}px`:`${E.bottom+w}px`),o(R).setAttribute("data-floating-position",Ae?"top":"bottom"),o(I)){let ie=o(I).getBoundingClientRect();o(I).style.left=E.left-Y+E.width/2-ie.width/2+"px"}}async function Uo(h,v){let w=await Gt("onRequestServerVerification",h,v);if(w!==void 0)return w;if(H("requesting server verification from",o(b).verifyUrl),!o(b).verifyUrl)throw new Error("Parameter verifyUrl must be set for server verification.");let E=await o(b).fetch(yr(o(b).verifyUrl,o(oe)),{body:JSON.stringify({code:v,fields:o(b).serverVerificationFields?Mo():void 0,payload:h,timeZone:o(b).serverVerificationTimeZone?Fo():void 0}),credentials:o(b).credentials||void 0,headers:{"Content-Type":"application/json"},method:"POST"});await Mi(E);let O=await E.json();return O&&typeof O=="object"&&"payload"in O&&O.payload&&S("serververification",O),O}function $i(h){o(P)&&"requestSubmit"in o(P)?o(P).requestSubmit(h):o(P)?.reportValidity()&&(h?h.click():o(P).submit())}function Ko(h,v={}){let{domain:w,name:E=o(b).name,maxAge:O,path:C,sameSite:j,secure:Ae}=v,Y=`${encodeURIComponent(E)}=${encodeURIComponent(h)}`;w&&(Y+=`; Domain=${w}`),O!=null&&(Y+=`; Max-Age=${O}`),C&&(Y+=`; Path=${C}`),j&&(Y+=`; SameSite=${j}`),Ae&&(Y+="; Secure"),document.cookie=Y}function wr(h){switch(h){case"bar":case"floating":case"overlay":rn(),(!o(Te)||o(Te)==="off")&&(o(z).auto="onsubmit");break;case"standard":nn()}o(He)!==h&&_(He,h,!0)}function zo(h){o(ye)&&clearTimeout(o(ye));let v=()=>{o(k)!==V.UNVERIFIED?(_(ne,!1),Me(V.EXPIRED)):$e(),S("expired")},w=h*1e3-Date.now();w>=1?_(ye,setTimeout(v,w),!0):v()}async function Mi(h){if(h.status>=400){if(h.headers.get("content-type")?.includes("/json")){let w;try{w=await h.json()}catch{}if(w&&"error"in w)throw new Error(`Server responded with ${h.status} - ${w.error}`)}throw new Error(`Server responded with ${h.status}.`)}let v=h.headers.get("content-type");if(!v||!v.includes("/json"))throw new Error(`Server responded with invalid content-type. Expected application/json, received ${v}.`)}async function Fi(h){if(!o(X)){Me(V.ERROR,"Cannot verify code challenge without PoW payload.");return}Me(V.VERIFYING);let v=null;if(o(b).verifyUrl)v=await Uo(o(X),h);else if(o(b).verifyFunction)v=await o(b).verifyFunction(o(X),h);else{Me(V.ERROR,"Parameter verifyUrl is required for code challenge verification.");return}v?.payload&&(_(X,v.payload,!0),H("server payload",o(X))),v?.verified===!0?(H("verified"),Me(V.VERIFIED),S("verified",{payload:o(X)}),o(Te)==="onsubmit"&&xt().then(()=>{$i(o(Re))})):Me(V.ERROR,v?.reason||"Verification failed."),o(b).disableAutoFocus||tn()?.focus()}function Jt(h){Object.assign(o(z),{...Object.fromEntries(Object.entries(h).filter(([v,w])=>w!==void 0))})}function Ho(){return{...o(b)}}function qo(){return o(k)}function rn(){_(ke,!1)}function H(...h){(o(b).debug||h.some(v=>v instanceof Error))&&console[h[0]instanceof Error?"error":"log"]("ALTCHA",`[name=${o(b).name}]`,...h)}function $e(h=V.UNVERIFIED,v=null){_(ne,!1),_(ge,v,!0),_(X,null),o(K)&&o(K).abort(),o(ye)&&(clearTimeout(o(ye)),_(ye,null)),Me(h)}function Me(h,v=null){_(k,h,!0),_(ge,v,!0),S("statechange",{payload:o(X),state:o(k)})}function nn(){_(ke,!0),xt().then(()=>{_r()})}function _r(){if(o(b).display==="floating")return jo();_(x,o(x)+1)}async function Nt(h={}){let{concurrency:v=Math.max(1,o(b).workers),controller:w=new AbortController,minDuration:E=o(b).minDuration}=h,O=performance.now(),C=null,j=null,Ae=!1,Y=await Gt("onVerify",h);if(Y!==void 0)return Y;$e(V.VERIFYING),_(K,w,!0);try{if(!u)throw new Error("Secure context (HTTPS) required.");if(o(b).mockError)throw new Error("Mock error.");if(o(b).test)return H("running test mode with null challenge"),await Ye(Math.max(0,E-(performance.now()-O))),o(K)?.signal.aborted?($e(),null):(_(X,btoa(JSON.stringify({challenge:null,solution:null,test:!0})),!0),H("verified"),Me(V.VERIFIED),S("verified",{payload:o(X)}),{payload:o(X)});if(C=await Ci(),!C)throw new Error("Failed to fetch challenge.");H("challenge",C),"configuration"in C&&(H("re-configuring from challenge",C.configuration),Jt(C.configuration)),C.parameters.expiresAt&&zo(C.parameters.expiresAt),Ae="_version"in C&&C._version===1;let ie=globalThis.$altcha.algorithms.get(C.parameters.algorithm);if(!ie)throw new Error(`Unsupported algorithm ${C.parameters.algorithm}.`);if(j=await co({challenge:C,concurrency:v,controller:w,createWorker:ie,counterMode:Ae?"string":"uint32",onOutOfMemory:ut=>{if(H("out of memory error received"),S("outofmemory"),o(b).retryOnOutOfMemoryError&&ut>1){let ht=Math.floor(ut/2);return H(`retrying with ${ht} workers...`),ht}},timeout:o(b).timeout}),o(K)?.signal.aborted)return $e(),null;if(!j)throw new Error("Failed to find solution.");H("solution",j),await Ye(Math.max(0,E-(performance.now()-O))),_(ae,C.codeChallenge||o(b).codeChallenge||null,!0),Ae?_(X,btoa(JSON.stringify(we(C,j))),!0):_(X,btoa(JSON.stringify({challenge:{parameters:C.parameters,signature:C.signature},solution:j})),!0),o(ae)?(H("requesting code verification"),Me(V.CODE),S("codechallenge",{codeChallenge:o(ae)})):o(b).verifyUrl?await Fi():(H("verified"),Me(V.VERIFIED),S("verified",{payload:o(X)}))}catch(ie){return H("verification failed",ie),Me(V.ERROR,String(ie)),null}finally{_(K,null)}return{challenge:C,payload:o(X),solution:j}}var Yo={configure:Jt,getConfiguration:Ho,getState:qo,hide:rn,log:H,reset:$e,setState:Me,show:nn,updateUI:_r,verify:Nt},Ni=Oc();ce("scroll",_n,Do),ce("click",_n,Ro),ce("pageshow",Ct,Bo),ce("resize",Ct,Po);var Ii=Dt(Ni);{var Wo=h=>{var v=bc();L(h,v)};le(Ii,h=>{o(b).display==="overlay"&&o(ke)&&h(Wo)})}var We=G(Ii,2),Li=Q(We);{var Go=h=>{var v=wc(),w=Dt(v),E=G(w,2);{var O=C=>{var j=yc();Qs(j,()=>document.querySelector(o(b).overlayContent)?.innerHTML,!0),W(j),L(C,j)};le(E,C=>{o(b).overlayContent&&C(O)})}ce("click",w,Lo,!0),L(h,v)};le(Li,h=>{o(b).display==="overlay"&&o(ke)&&h(Go)})}var sn=G(Li,2),on=Q(sn),an=Q(on),Di=Q(an);{let h=me(()=>o(b).display==="standard"&&o(Te)!=="onsubmit"||o(k)===V.VERIFYING);Fl(Di,()=>o(br),(v,w)=>{w(v,{get id(){return o(wt)},name:"",get required(){return o(h)},get loading(){return o(Zr)},get checked(){return o(ne)},onchange:No,oninvalid:Io})})}var ln=G(Di,2),Jo=Q(ln);{var Zo=h=>{var v=kr();ve(()=>Ge(v,o(y).verificationRequired)),L(h,v)},Xo=h=>{var v=kr();ve(()=>Ge(v,o(y).verifying)),L(h,v)},Qo=h=>{var v=kr();ve(()=>Ge(v,o(y).verified)),L(h,v)},ea=h=>{var v=kr();ve(()=>Ge(v,o(y).label)),L(h,v)};le(Jo,h=>{o(k)===V.CODE&&o(ae)?h(Zo):o(k)===V.VERIFYING?h(Xo,1):o(k)===V.VERIFIED?h(Qo,2):h(ea,-1)})}W(ln),W(an);var ta=G(an,2);{var ra=h=>{Xn(h,{get strings(){return o(y)}})};le(ta,h=>{o(qt)&&h(ra)})}W(on);var Ri=G(on,2);{var na=h=>{{let v=me(()=>o(b).display==="bar"&&o(qt));Fn(h,{get logo(){return o(v)},get strings(){return o(y)}})}};le(Ri,h=>{o(_t)&&h(na)})}var Bi=G(Ri,2);{var ia=h=>{var v=_c();mt(v,w=>_(I,w),()=>o(I)),L(h,v)};le(Bi,h=>{o(b).display==="floating"&&h(ia)})}var sa=G(Bi,2);{var oa=h=>{var v=Ec();Jn(v),ve(()=>{U(v,"name",o(b).name),ql(v,o(X))}),L(h,v)};le(sa,h=>{o(b).setCookie||h(oa)})}W(sn);var aa=G(sn,2);{var la=h=>{Nn(h,{get anchor(){return o(R)},onClickOutside:()=>{u&&$e()},get placement(){return o(b).popoverPlacement},role:"alert",variant:"error",get dir(){return o(Wt)},get updateUISignal(){return o(x)},children:(v,w)=>{var E=Ji(),O=Dt(E);{var C=Y=>{var ie=kc();L(Y,ie)},j=Y=>{var ie=Ac(),ut=Q(ie,!0);W(ie),ve(()=>Ge(ut,o(y).expired)),L(Y,ie)},Ae=Y=>{var ie=xc(),ut=Q(ie,!0);W(ie),ve(()=>{U(ie,"title",o(ge)),Ge(ut,o(y).error)}),L(Y,ie)};le(O,Y=>{!o(ge)&&!u?Y(C):!o(ge)&&o(k)===V.EXPIRED?Y(j,1):Y(Ae,-1)})}L(v,E)},$$slots:{default:!0}})},ca=h=>{var v=Ji(),w=Dt(v);Ml(w,()=>o(ae),E=>{{let O=me(()=>o(b).codeChallengeDisplay!=="standard");Nn(E,{get anchor(){return o(R)},get backdrop(){return o(O)},get display(){return o(b).codeChallengeDisplay},onClose:()=>{$e()},get placement(){return o(b).popoverPlacement},role:"dialog",get"aria-label"(){return o(y).verificationRequired},get dir(){return o(Wt)},get updateUISignal(){return o(x)},children:(C,j)=>{var Ae=Cc(),Y=Dt(Ae);lo(Y,{get audioUrl(){return o(te)},get imageUrl(){return o(Et)},onCancel:()=>$e(),onReload:()=>Nt(),onSubmit:ht=>Fi(ht),get codeChallenge(){return o(ae)},get config(){return o(b)},get strings(){return o(y)}});var ie=G(Y,2);{var ut=ht=>{Fn(ht,{get logo(){return o(qt)},get strings(){return o(y)}})};le(ie,ht=>{o(_t)&&o(b).codeChallengeDisplay!=="standard"&&ht(ut)})}L(C,Ae)},$$slots:{default:!0}})}}),L(h,v)};le(aa,h=>{o(ge)||o(k)===V.EXPIRED||!u?h(la):o(ae)&&o(k)===V.CODE&&h(ca,1)})}W(We),mt(We,h=>_(R,h),()=>o(R)),ve(h=>{U(We,"data-state",o(k)),U(We,"data-display",o(b).display||void 0),U(We,"data-placement",h),U(We,"data-visible",o(ke)||void 0),U(We,"dir",o(Wt)),U(ln,"for",o(wt)),We.dir=We.dir},[()=>$o(o(b).display)]),L(t,Ni);var ua=lt(Yo);return s(),ua}typeof window<"u"&&window.customElements&&customElements.define("altcha-widget",yt(Sc,{auto:{type:"String"},challenge:{type:"String"},configuration:{type:"String"},display:{type:"String"},language:{type:"String"},name:{type:"String"},theme:{type:"String"},type:{type:"String"},workers:{type:"Number"}},[],["configure","getConfiguration","getState","hide","log","reset","setState","show","updateUI","verify"]));var uo=`(function() {
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
`,ts=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",uo],{type:"text/javascript;charset=utf-8"});function Qn(t){let e;try{if(e=ts&&(self.URL||self.webkitURL).createObjectURL(ts),!e)throw"";let r=new Worker(e,{name:t?.name});return r.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),r}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(uo),{name:t?.name})}}var ho=`(function() {
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
`,rs=typeof self<"u"&&self.Blob&&new Blob(["(self.URL || self.webkitURL).revokeObjectURL(self.location.href);",ho],{type:"text/javascript;charset=utf-8"});function ei(t){let e;try{if(e=rs&&(self.URL||self.webkitURL).createObjectURL(rs),!e)throw"";let r=new Worker(e,{name:t?.name});return r.addEventListener("error",()=>{(self.URL||self.webkitURL).revokeObjectURL(e)}),r}catch{return new Worker("data:text/javascript;charset=utf-8,"+encodeURIComponent(ho),{name:t?.name})}}var Tc=`:root {
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
}`;mc(Tc);$altcha.algorithms.set("SHA-256",()=>new ei);$altcha.algorithms.set("SHA-384",()=>new ei);$altcha.algorithms.set("SHA-512",()=>new ei);$altcha.algorithms.set("PBKDF2/SHA-256",()=>new Qn);$altcha.algorithms.set("PBKDF2/SHA-384",()=>new Qn);$altcha.algorithms.set("PBKDF2/SHA-512",()=>new Qn);var $c={ariaLinkLabel:"Altcha (site officiel)",enterCode:"Entrez le code",enterCodeAria:"Entrez le code que vous entendez. Appuyez sur Espace pour \xE9couter l'audio.",error:"\xC9chec de la v\xE9rification. Essayez \xE0 nouveau plus tard.",expired:"La v\xE9rification a expir\xE9. Essayez \xE0 nouveau.",footer:'Prot\xE9g\xE9 par <a href="https://altcha.org/" tabindex="-1" target="_blank" aria-label="Altcha (site officiel)">ALTCHA</a>',getAudioChallenge:"Obtenir un d\xE9fi audio",label:"Je ne suis pas un robot",loading:"Chargement...",reload:"Recharger",verify:"V\xE9rifier",verificationRequired:"V\xE9rification requise !",verified:"V\xE9rifi\xE9",verifying:"V\xE9rification en cours...",waitAlert:"V\xE9rification en cours... veuillez patienter.",cancel:"Annuler",enterCodeFromImage:"Pour continuer, veuillez entrer le code de l'image ci-dessous."};"$altcha"in globalThis&&globalThis.$altcha.i18n.set("fr-fr",$c);var ti=class{constructor(e,r,n){this.eventTarget=e,this.eventName=r,this.eventOptions=n,this.unorderedBindings=new Set}connect(){this.eventTarget.addEventListener(this.eventName,this,this.eventOptions)}disconnect(){this.eventTarget.removeEventListener(this.eventName,this,this.eventOptions)}bindingConnected(e){this.unorderedBindings.add(e)}bindingDisconnected(e){this.unorderedBindings.delete(e)}handleEvent(e){let r=Mc(e);for(let n of this.bindings){if(r.immediatePropagationStopped)break;n.handleEvent(r)}}hasBindings(){return this.unorderedBindings.size>0}get bindings(){return Array.from(this.unorderedBindings).sort((e,r)=>{let n=e.index,i=r.index;return n<i?-1:n>i?1:0})}};function Mc(t){if("immediatePropagationStopped"in t)return t;{let{stopImmediatePropagation:e}=t;return Object.assign(t,{immediatePropagationStopped:!1,stopImmediatePropagation(){this.immediatePropagationStopped=!0,e.call(this)}})}}var ri=class{constructor(e){this.application=e,this.eventListenerMaps=new Map,this.started=!1}start(){this.started||(this.started=!0,this.eventListeners.forEach(e=>e.connect()))}stop(){this.started&&(this.started=!1,this.eventListeners.forEach(e=>e.disconnect()))}get eventListeners(){return Array.from(this.eventListenerMaps.values()).reduce((e,r)=>e.concat(Array.from(r.values())),[])}bindingConnected(e){this.fetchEventListenerForBinding(e).bindingConnected(e)}bindingDisconnected(e,r=!1){this.fetchEventListenerForBinding(e).bindingDisconnected(e),r&&this.clearEventListenersForBinding(e)}handleError(e,r,n={}){this.application.handleError(e,`Error ${r}`,n)}clearEventListenersForBinding(e){let r=this.fetchEventListenerForBinding(e);r.hasBindings()||(r.disconnect(),this.removeMappedEventListenerFor(e))}removeMappedEventListenerFor(e){let{eventTarget:r,eventName:n,eventOptions:i}=e,s=this.fetchEventListenerMapForEventTarget(r),a=this.cacheKey(n,i);s.delete(a),s.size==0&&this.eventListenerMaps.delete(r)}fetchEventListenerForBinding(e){let{eventTarget:r,eventName:n,eventOptions:i}=e;return this.fetchEventListener(r,n,i)}fetchEventListener(e,r,n){let i=this.fetchEventListenerMapForEventTarget(e),s=this.cacheKey(r,n),a=i.get(s);return a||(a=this.createEventListener(e,r,n),i.set(s,a)),a}createEventListener(e,r,n){let i=new ti(e,r,n);return this.started&&i.connect(),i}fetchEventListenerMapForEventTarget(e){let r=this.eventListenerMaps.get(e);return r||(r=new Map,this.eventListenerMaps.set(e,r)),r}cacheKey(e,r){let n=[e];return Object.keys(r).sort().forEach(i=>{n.push(`${r[i]?"":"!"}${i}`)}),n.join(":")}},Fc={stop({event:t,value:e}){return e&&t.stopPropagation(),!0},prevent({event:t,value:e}){return e&&t.preventDefault(),!0},self({event:t,value:e,element:r}){return e?r===t.target:!0}},Nc=/^(?:(?:([^.]+?)\+)?(.+?)(?:\.(.+?))?(?:@(window|document))?->)?(.+?)(?:#([^:]+?))(?::(.+))?$/;function Ic(t){let r=t.trim().match(Nc)||[],n=r[2],i=r[3];return i&&!["keydown","keyup","keypress"].includes(n)&&(n+=`.${i}`,i=""),{eventTarget:Lc(r[4]),eventName:n,eventOptions:r[7]?Dc(r[7]):{},identifier:r[5],methodName:r[6],keyFilter:r[1]||i}}function Lc(t){if(t=="window")return window;if(t=="document")return document}function Dc(t){return t.split(":").reduce((e,r)=>Object.assign(e,{[r.replace(/^!/,"")]:!/^!/.test(r)}),{})}function Rc(t){if(t==window)return"window";if(t==document)return"document"}function Ai(t){return t.replace(/(?:[_-])([a-z0-9])/g,(e,r)=>r.toUpperCase())}function ni(t){return Ai(t.replace(/--/g,"-").replace(/__/g,"_"))}function gr(t){return t.charAt(0).toUpperCase()+t.slice(1)}function Eo(t){return t.replace(/([A-Z])/g,(e,r)=>`-${r.toLowerCase()}`)}function Bc(t){return t.match(/[^\s]+/g)||[]}function fo(t){return t!=null}function ii(t,e){return Object.prototype.hasOwnProperty.call(t,e)}var po=["meta","ctrl","alt","shift"],si=class{constructor(e,r,n,i){this.element=e,this.index=r,this.eventTarget=n.eventTarget||e,this.eventName=n.eventName||Pc(e)||zr("missing event name"),this.eventOptions=n.eventOptions||{},this.identifier=n.identifier||zr("missing identifier"),this.methodName=n.methodName||zr("missing method name"),this.keyFilter=n.keyFilter||"",this.schema=i}static forToken(e,r){return new this(e.element,e.index,Ic(e.content),r)}toString(){let e=this.keyFilter?`.${this.keyFilter}`:"",r=this.eventTargetName?`@${this.eventTargetName}`:"";return`${this.eventName}${e}${r}->${this.identifier}#${this.methodName}`}shouldIgnoreKeyboardEvent(e){if(!this.keyFilter)return!1;let r=this.keyFilter.split("+");if(this.keyFilterDissatisfied(e,r))return!0;let n=r.filter(i=>!po.includes(i))[0];return n?(ii(this.keyMappings,n)||zr(`contains unknown key filter: ${this.keyFilter}`),this.keyMappings[n].toLowerCase()!==e.key.toLowerCase()):!1}shouldIgnoreMouseEvent(e){if(!this.keyFilter)return!1;let r=[this.keyFilter];return!!this.keyFilterDissatisfied(e,r)}get params(){let e={},r=new RegExp(`^data-${this.identifier}-(.+)-param$`,"i");for(let{name:n,value:i}of Array.from(this.element.attributes)){let s=n.match(r),a=s&&s[1];a&&(e[Ai(a)]=Vc(i))}return e}get eventTargetName(){return Rc(this.eventTarget)}get keyMappings(){return this.schema.keyMappings}keyFilterDissatisfied(e,r){let[n,i,s,a]=po.map(l=>r.includes(l));return e.metaKey!==n||e.ctrlKey!==i||e.altKey!==s||e.shiftKey!==a}},vo={a:()=>"click",button:()=>"click",form:()=>"submit",details:()=>"toggle",input:t=>t.getAttribute("type")=="submit"?"click":"input",select:()=>"change",textarea:()=>"input"};function Pc(t){let e=t.tagName.toLowerCase();if(e in vo)return vo[e](t)}function zr(t){throw new Error(t)}function Vc(t){try{return JSON.parse(t)}catch{return t}}var oi=class{constructor(e,r){this.context=e,this.action=r}get index(){return this.action.index}get eventTarget(){return this.action.eventTarget}get eventOptions(){return this.action.eventOptions}get identifier(){return this.context.identifier}handleEvent(e){let r=this.prepareActionEvent(e);this.willBeInvokedByEvent(e)&&this.applyEventModifiers(r)&&this.invokeWithEvent(r)}get eventName(){return this.action.eventName}get method(){let e=this.controller[this.methodName];if(typeof e=="function")return e;throw new Error(`Action "${this.action}" references undefined method "${this.methodName}"`)}applyEventModifiers(e){let{element:r}=this.action,{actionDescriptorFilters:n}=this.context.application,{controller:i}=this.context,s=!0;for(let[a,l]of Object.entries(this.eventOptions))if(a in n){let c=n[a];s=s&&c({name:a,value:l,event:e,element:r,controller:i})}else continue;return s}prepareActionEvent(e){return Object.assign(e,{params:this.action.params})}invokeWithEvent(e){let{target:r,currentTarget:n}=e;try{this.method.call(this.controller,e),this.context.logDebugActivity(this.methodName,{event:e,target:r,currentTarget:n,action:this.methodName})}catch(i){let{identifier:s,controller:a,element:l,index:c}=this,u={identifier:s,controller:a,element:l,index:c,event:e};this.context.handleError(i,`invoking action "${this.action}"`,u)}}willBeInvokedByEvent(e){let r=e.target;return e instanceof KeyboardEvent&&this.action.shouldIgnoreKeyboardEvent(e)||e instanceof MouseEvent&&this.action.shouldIgnoreMouseEvent(e)?!1:this.element===r?!0:r instanceof Element&&this.element.contains(r)?this.scope.containsElement(r):this.scope.containsElement(this.action.element)}get controller(){return this.context.controller}get methodName(){return this.action.methodName}get element(){return this.scope.element}get scope(){return this.context.scope}},Hr=class{constructor(e,r){this.mutationObserverInit={attributes:!0,childList:!0,subtree:!0},this.element=e,this.started=!1,this.delegate=r,this.elements=new Set,this.mutationObserver=new MutationObserver(n=>this.processMutations(n))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,this.mutationObserverInit),this.refresh())}pause(e){this.started&&(this.mutationObserver.disconnect(),this.started=!1),e(),this.started||(this.mutationObserver.observe(this.element,this.mutationObserverInit),this.started=!0)}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started){let e=new Set(this.matchElementsInTree());for(let r of Array.from(this.elements))e.has(r)||this.removeElement(r);for(let r of Array.from(e))this.addElement(r)}}processMutations(e){if(this.started)for(let r of e)this.processMutation(r)}processMutation(e){e.type=="attributes"?this.processAttributeChange(e.target,e.attributeName):e.type=="childList"&&(this.processRemovedNodes(e.removedNodes),this.processAddedNodes(e.addedNodes))}processAttributeChange(e,r){this.elements.has(e)?this.delegate.elementAttributeChanged&&this.matchElement(e)?this.delegate.elementAttributeChanged(e,r):this.removeElement(e):this.matchElement(e)&&this.addElement(e)}processRemovedNodes(e){for(let r of Array.from(e)){let n=this.elementFromNode(r);n&&this.processTree(n,this.removeElement)}}processAddedNodes(e){for(let r of Array.from(e)){let n=this.elementFromNode(r);n&&this.elementIsActive(n)&&this.processTree(n,this.addElement)}}matchElement(e){return this.delegate.matchElement(e)}matchElementsInTree(e=this.element){return this.delegate.matchElementsInTree(e)}processTree(e,r){for(let n of this.matchElementsInTree(e))r.call(this,n)}elementFromNode(e){if(e.nodeType==Node.ELEMENT_NODE)return e}elementIsActive(e){return e.isConnected!=this.element.isConnected?!1:this.element.contains(e)}addElement(e){this.elements.has(e)||this.elementIsActive(e)&&(this.elements.add(e),this.delegate.elementMatched&&this.delegate.elementMatched(e))}removeElement(e){this.elements.has(e)&&(this.elements.delete(e),this.delegate.elementUnmatched&&this.delegate.elementUnmatched(e))}},qr=class{constructor(e,r,n){this.attributeName=r,this.delegate=n,this.elementObserver=new Hr(e,this)}get element(){return this.elementObserver.element}get selector(){return`[${this.attributeName}]`}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get started(){return this.elementObserver.started}matchElement(e){return e.hasAttribute(this.attributeName)}matchElementsInTree(e){let r=this.matchElement(e)?[e]:[],n=Array.from(e.querySelectorAll(this.selector));return r.concat(n)}elementMatched(e){this.delegate.elementMatchedAttribute&&this.delegate.elementMatchedAttribute(e,this.attributeName)}elementUnmatched(e){this.delegate.elementUnmatchedAttribute&&this.delegate.elementUnmatchedAttribute(e,this.attributeName)}elementAttributeChanged(e,r){this.delegate.elementAttributeValueChanged&&this.attributeName==r&&this.delegate.elementAttributeValueChanged(e,r)}};function jc(t,e,r){ko(t,e).add(r)}function Uc(t,e,r){ko(t,e).delete(r),Kc(t,e)}function ko(t,e){let r=t.get(e);return r||(r=new Set,t.set(e,r)),r}function Kc(t,e){let r=t.get(e);r!=null&&r.size==0&&t.delete(e)}var ct=class{constructor(){this.valuesByKey=new Map}get keys(){return Array.from(this.valuesByKey.keys())}get values(){return Array.from(this.valuesByKey.values()).reduce((r,n)=>r.concat(Array.from(n)),[])}get size(){return Array.from(this.valuesByKey.values()).reduce((r,n)=>r+n.size,0)}add(e,r){jc(this.valuesByKey,e,r)}delete(e,r){Uc(this.valuesByKey,e,r)}has(e,r){let n=this.valuesByKey.get(e);return n!=null&&n.has(r)}hasKey(e){return this.valuesByKey.has(e)}hasValue(e){return Array.from(this.valuesByKey.values()).some(n=>n.has(e))}getValuesForKey(e){let r=this.valuesByKey.get(e);return r?Array.from(r):[]}getKeysForValue(e){return Array.from(this.valuesByKey).filter(([r,n])=>n.has(e)).map(([r,n])=>r)}};var ai=class{constructor(e,r,n,i){this._selector=r,this.details=i,this.elementObserver=new Hr(e,this),this.delegate=n,this.matchesByElement=new ct}get started(){return this.elementObserver.started}get selector(){return this._selector}set selector(e){this._selector=e,this.refresh()}start(){this.elementObserver.start()}pause(e){this.elementObserver.pause(e)}stop(){this.elementObserver.stop()}refresh(){this.elementObserver.refresh()}get element(){return this.elementObserver.element}matchElement(e){let{selector:r}=this;if(r){let n=e.matches(r);return this.delegate.selectorMatchElement?n&&this.delegate.selectorMatchElement(e,this.details):n}else return!1}matchElementsInTree(e){let{selector:r}=this;if(r){let n=this.matchElement(e)?[e]:[],i=Array.from(e.querySelectorAll(r)).filter(s=>this.matchElement(s));return n.concat(i)}else return[]}elementMatched(e){let{selector:r}=this;r&&this.selectorMatched(e,r)}elementUnmatched(e){let r=this.matchesByElement.getKeysForValue(e);for(let n of r)this.selectorUnmatched(e,n)}elementAttributeChanged(e,r){let{selector:n}=this;if(n){let i=this.matchElement(e),s=this.matchesByElement.has(n,e);i&&!s?this.selectorMatched(e,n):!i&&s&&this.selectorUnmatched(e,n)}}selectorMatched(e,r){this.delegate.selectorMatched(e,r,this.details),this.matchesByElement.add(r,e)}selectorUnmatched(e,r){this.delegate.selectorUnmatched(e,r,this.details),this.matchesByElement.delete(r,e)}},li=class{constructor(e,r){this.element=e,this.delegate=r,this.started=!1,this.stringMap=new Map,this.mutationObserver=new MutationObserver(n=>this.processMutations(n))}start(){this.started||(this.started=!0,this.mutationObserver.observe(this.element,{attributes:!0,attributeOldValue:!0}),this.refresh())}stop(){this.started&&(this.mutationObserver.takeRecords(),this.mutationObserver.disconnect(),this.started=!1)}refresh(){if(this.started)for(let e of this.knownAttributeNames)this.refreshAttribute(e,null)}processMutations(e){if(this.started)for(let r of e)this.processMutation(r)}processMutation(e){let r=e.attributeName;r&&this.refreshAttribute(r,e.oldValue)}refreshAttribute(e,r){let n=this.delegate.getStringMapKeyForAttribute(e);if(n!=null){this.stringMap.has(e)||this.stringMapKeyAdded(n,e);let i=this.element.getAttribute(e);if(this.stringMap.get(e)!=i&&this.stringMapValueChanged(i,n,r),i==null){let s=this.stringMap.get(e);this.stringMap.delete(e),s&&this.stringMapKeyRemoved(n,e,s)}else this.stringMap.set(e,i)}}stringMapKeyAdded(e,r){this.delegate.stringMapKeyAdded&&this.delegate.stringMapKeyAdded(e,r)}stringMapValueChanged(e,r,n){this.delegate.stringMapValueChanged&&this.delegate.stringMapValueChanged(e,r,n)}stringMapKeyRemoved(e,r,n){this.delegate.stringMapKeyRemoved&&this.delegate.stringMapKeyRemoved(e,r,n)}get knownAttributeNames(){return Array.from(new Set(this.currentAttributeNames.concat(this.recordedAttributeNames)))}get currentAttributeNames(){return Array.from(this.element.attributes).map(e=>e.name)}get recordedAttributeNames(){return Array.from(this.stringMap.keys())}},Yr=class{constructor(e,r,n){this.attributeObserver=new qr(e,r,this),this.delegate=n,this.tokensByElement=new ct}get started(){return this.attributeObserver.started}start(){this.attributeObserver.start()}pause(e){this.attributeObserver.pause(e)}stop(){this.attributeObserver.stop()}refresh(){this.attributeObserver.refresh()}get element(){return this.attributeObserver.element}get attributeName(){return this.attributeObserver.attributeName}elementMatchedAttribute(e){this.tokensMatched(this.readTokensForElement(e))}elementAttributeValueChanged(e){let[r,n]=this.refreshTokensForElement(e);this.tokensUnmatched(r),this.tokensMatched(n)}elementUnmatchedAttribute(e){this.tokensUnmatched(this.tokensByElement.getValuesForKey(e))}tokensMatched(e){e.forEach(r=>this.tokenMatched(r))}tokensUnmatched(e){e.forEach(r=>this.tokenUnmatched(r))}tokenMatched(e){this.delegate.tokenMatched(e),this.tokensByElement.add(e.element,e)}tokenUnmatched(e){this.delegate.tokenUnmatched(e),this.tokensByElement.delete(e.element,e)}refreshTokensForElement(e){let r=this.tokensByElement.getValuesForKey(e),n=this.readTokensForElement(e),i=Hc(r,n).findIndex(([s,a])=>!qc(s,a));return i==-1?[[],[]]:[r.slice(i),n.slice(i)]}readTokensForElement(e){let r=this.attributeName,n=e.getAttribute(r)||"";return zc(n,e,r)}};function zc(t,e,r){return t.trim().split(/\s+/).filter(n=>n.length).map((n,i)=>({element:e,attributeName:r,content:n,index:i}))}function Hc(t,e){let r=Math.max(t.length,e.length);return Array.from({length:r},(n,i)=>[t[i],e[i]])}function qc(t,e){return t&&e&&t.index==e.index&&t.content==e.content}var Wr=class{constructor(e,r,n){this.tokenListObserver=new Yr(e,r,this),this.delegate=n,this.parseResultsByToken=new WeakMap,this.valuesByTokenByElement=new WeakMap}get started(){return this.tokenListObserver.started}start(){this.tokenListObserver.start()}stop(){this.tokenListObserver.stop()}refresh(){this.tokenListObserver.refresh()}get element(){return this.tokenListObserver.element}get attributeName(){return this.tokenListObserver.attributeName}tokenMatched(e){let{element:r}=e,{value:n}=this.fetchParseResultForToken(e);n&&(this.fetchValuesByTokenForElement(r).set(e,n),this.delegate.elementMatchedValue(r,n))}tokenUnmatched(e){let{element:r}=e,{value:n}=this.fetchParseResultForToken(e);n&&(this.fetchValuesByTokenForElement(r).delete(e),this.delegate.elementUnmatchedValue(r,n))}fetchParseResultForToken(e){let r=this.parseResultsByToken.get(e);return r||(r=this.parseToken(e),this.parseResultsByToken.set(e,r)),r}fetchValuesByTokenForElement(e){let r=this.valuesByTokenByElement.get(e);return r||(r=new Map,this.valuesByTokenByElement.set(e,r)),r}parseToken(e){try{return{value:this.delegate.parseValueForToken(e)}}catch(r){return{error:r}}}},ci=class{constructor(e,r){this.context=e,this.delegate=r,this.bindingsByAction=new Map}start(){this.valueListObserver||(this.valueListObserver=new Wr(this.element,this.actionAttribute,this),this.valueListObserver.start())}stop(){this.valueListObserver&&(this.valueListObserver.stop(),delete this.valueListObserver,this.disconnectAllActions())}get element(){return this.context.element}get identifier(){return this.context.identifier}get actionAttribute(){return this.schema.actionAttribute}get schema(){return this.context.schema}get bindings(){return Array.from(this.bindingsByAction.values())}connectAction(e){let r=new oi(this.context,e);this.bindingsByAction.set(e,r),this.delegate.bindingConnected(r)}disconnectAction(e){let r=this.bindingsByAction.get(e);r&&(this.bindingsByAction.delete(e),this.delegate.bindingDisconnected(r))}disconnectAllActions(){this.bindings.forEach(e=>this.delegate.bindingDisconnected(e,!0)),this.bindingsByAction.clear()}parseValueForToken(e){let r=si.forToken(e,this.schema);if(r.identifier==this.identifier)return r}elementMatchedValue(e,r){this.connectAction(r)}elementUnmatchedValue(e,r){this.disconnectAction(r)}},ui=class{constructor(e,r){this.context=e,this.receiver=r,this.stringMapObserver=new li(this.element,this),this.valueDescriptorMap=this.controller.valueDescriptorMap}start(){this.stringMapObserver.start(),this.invokeChangedCallbacksForDefaultValues()}stop(){this.stringMapObserver.stop()}get element(){return this.context.element}get controller(){return this.context.controller}getStringMapKeyForAttribute(e){if(e in this.valueDescriptorMap)return this.valueDescriptorMap[e].name}stringMapKeyAdded(e,r){let n=this.valueDescriptorMap[r];this.hasValue(e)||this.invokeChangedCallback(e,n.writer(this.receiver[e]),n.writer(n.defaultValue))}stringMapValueChanged(e,r,n){let i=this.valueDescriptorNameMap[r];e!==null&&(n===null&&(n=i.writer(i.defaultValue)),this.invokeChangedCallback(r,e,n))}stringMapKeyRemoved(e,r,n){let i=this.valueDescriptorNameMap[e];this.hasValue(e)?this.invokeChangedCallback(e,i.writer(this.receiver[e]),n):this.invokeChangedCallback(e,i.writer(i.defaultValue),n)}invokeChangedCallbacksForDefaultValues(){for(let{key:e,name:r,defaultValue:n,writer:i}of this.valueDescriptors)n!=null&&!this.controller.data.has(e)&&this.invokeChangedCallback(r,i(n),void 0)}invokeChangedCallback(e,r,n){let i=`${e}Changed`,s=this.receiver[i];if(typeof s=="function"){let a=this.valueDescriptorNameMap[e];try{let l=a.reader(r),c=n;n&&(c=a.reader(n)),s.call(this.receiver,l,c)}catch(l){throw l instanceof TypeError&&(l.message=`Stimulus Value "${this.context.identifier}.${a.name}" - ${l.message}`),l}}}get valueDescriptors(){let{valueDescriptorMap:e}=this;return Object.keys(e).map(r=>e[r])}get valueDescriptorNameMap(){let e={};return Object.keys(this.valueDescriptorMap).forEach(r=>{let n=this.valueDescriptorMap[r];e[n.name]=n}),e}hasValue(e){let r=this.valueDescriptorNameMap[e],n=`has${gr(r.name)}`;return this.receiver[n]}},hi=class{constructor(e,r){this.context=e,this.delegate=r,this.targetsByName=new ct}start(){this.tokenListObserver||(this.tokenListObserver=new Yr(this.element,this.attributeName,this),this.tokenListObserver.start())}stop(){this.tokenListObserver&&(this.disconnectAllTargets(),this.tokenListObserver.stop(),delete this.tokenListObserver)}tokenMatched({element:e,content:r}){this.scope.containsElement(e)&&this.connectTarget(e,r)}tokenUnmatched({element:e,content:r}){this.disconnectTarget(e,r)}connectTarget(e,r){var n;this.targetsByName.has(r,e)||(this.targetsByName.add(r,e),(n=this.tokenListObserver)===null||n===void 0||n.pause(()=>this.delegate.targetConnected(e,r)))}disconnectTarget(e,r){var n;this.targetsByName.has(r,e)&&(this.targetsByName.delete(r,e),(n=this.tokenListObserver)===null||n===void 0||n.pause(()=>this.delegate.targetDisconnected(e,r)))}disconnectAllTargets(){for(let e of this.targetsByName.keys)for(let r of this.targetsByName.getValuesForKey(e))this.disconnectTarget(r,e)}get attributeName(){return`data-${this.context.identifier}-target`}get element(){return this.context.element}get scope(){return this.context.scope}};function mr(t,e){let r=Ao(t);return Array.from(r.reduce((n,i)=>(Wc(i,e).forEach(s=>n.add(s)),n),new Set))}function Yc(t,e){return Ao(t).reduce((n,i)=>(n.push(...Gc(i,e)),n),[])}function Ao(t){let e=[];for(;t;)e.push(t),t=Object.getPrototypeOf(t);return e.reverse()}function Wc(t,e){let r=t[e];return Array.isArray(r)?r:[]}function Gc(t,e){let r=t[e];return r?Object.keys(r).map(n=>[n,r[n]]):[]}var fi=class{constructor(e,r){this.started=!1,this.context=e,this.delegate=r,this.outletsByName=new ct,this.outletElementsByName=new ct,this.selectorObserverMap=new Map,this.attributeObserverMap=new Map}start(){this.started||(this.outletDefinitions.forEach(e=>{this.setupSelectorObserverForOutlet(e),this.setupAttributeObserverForOutlet(e)}),this.started=!0,this.dependentContexts.forEach(e=>e.refresh()))}refresh(){this.selectorObserverMap.forEach(e=>e.refresh()),this.attributeObserverMap.forEach(e=>e.refresh())}stop(){this.started&&(this.started=!1,this.disconnectAllOutlets(),this.stopSelectorObservers(),this.stopAttributeObservers())}stopSelectorObservers(){this.selectorObserverMap.size>0&&(this.selectorObserverMap.forEach(e=>e.stop()),this.selectorObserverMap.clear())}stopAttributeObservers(){this.attributeObserverMap.size>0&&(this.attributeObserverMap.forEach(e=>e.stop()),this.attributeObserverMap.clear())}selectorMatched(e,r,{outletName:n}){let i=this.getOutlet(e,n);i&&this.connectOutlet(i,e,n)}selectorUnmatched(e,r,{outletName:n}){let i=this.getOutletFromMap(e,n);i&&this.disconnectOutlet(i,e,n)}selectorMatchElement(e,{outletName:r}){let n=this.selector(r),i=this.hasOutlet(e,r),s=e.matches(`[${this.schema.controllerAttribute}~=${r}]`);return n?i&&s&&e.matches(n):!1}elementMatchedAttribute(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}elementAttributeValueChanged(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}elementUnmatchedAttribute(e,r){let n=this.getOutletNameFromOutletAttributeName(r);n&&this.updateSelectorObserverForOutlet(n)}connectOutlet(e,r,n){var i;this.outletElementsByName.has(n,r)||(this.outletsByName.add(n,e),this.outletElementsByName.add(n,r),(i=this.selectorObserverMap.get(n))===null||i===void 0||i.pause(()=>this.delegate.outletConnected(e,r,n)))}disconnectOutlet(e,r,n){var i;this.outletElementsByName.has(n,r)&&(this.outletsByName.delete(n,e),this.outletElementsByName.delete(n,r),(i=this.selectorObserverMap.get(n))===null||i===void 0||i.pause(()=>this.delegate.outletDisconnected(e,r,n)))}disconnectAllOutlets(){for(let e of this.outletElementsByName.keys)for(let r of this.outletElementsByName.getValuesForKey(e))for(let n of this.outletsByName.getValuesForKey(e))this.disconnectOutlet(n,r,e)}updateSelectorObserverForOutlet(e){let r=this.selectorObserverMap.get(e);r&&(r.selector=this.selector(e))}setupSelectorObserverForOutlet(e){let r=this.selector(e),n=new ai(document.body,r,this,{outletName:e});this.selectorObserverMap.set(e,n),n.start()}setupAttributeObserverForOutlet(e){let r=this.attributeNameForOutletName(e),n=new qr(this.scope.element,r,this);this.attributeObserverMap.set(e,n),n.start()}selector(e){return this.scope.outlets.getSelectorForOutletName(e)}attributeNameForOutletName(e){return this.scope.schema.outletAttributeForScope(this.identifier,e)}getOutletNameFromOutletAttributeName(e){return this.outletDefinitions.find(r=>this.attributeNameForOutletName(r)===e)}get outletDependencies(){let e=new ct;return this.router.modules.forEach(r=>{let n=r.definition.controllerConstructor;mr(n,"outlets").forEach(s=>e.add(s,r.identifier))}),e}get outletDefinitions(){return this.outletDependencies.getKeysForValue(this.identifier)}get dependentControllerIdentifiers(){return this.outletDependencies.getValuesForKey(this.identifier)}get dependentContexts(){let e=this.dependentControllerIdentifiers;return this.router.contexts.filter(r=>e.includes(r.identifier))}hasOutlet(e,r){return!!this.getOutlet(e,r)||!!this.getOutletFromMap(e,r)}getOutlet(e,r){return this.application.getControllerForElementAndIdentifier(e,r)}getOutletFromMap(e,r){return this.outletsByName.getValuesForKey(r).find(n=>n.element===e)}get scope(){return this.context.scope}get schema(){return this.context.schema}get identifier(){return this.context.identifier}get application(){return this.context.application}get router(){return this.application.router}},di=class{constructor(e,r){this.logDebugActivity=(n,i={})=>{let{identifier:s,controller:a,element:l}=this;i=Object.assign({identifier:s,controller:a,element:l},i),this.application.logDebugActivity(this.identifier,n,i)},this.module=e,this.scope=r,this.controller=new e.controllerConstructor(this),this.bindingObserver=new ci(this,this.dispatcher),this.valueObserver=new ui(this,this.controller),this.targetObserver=new hi(this,this),this.outletObserver=new fi(this,this);try{this.controller.initialize(),this.logDebugActivity("initialize")}catch(n){this.handleError(n,"initializing controller")}}connect(){this.bindingObserver.start(),this.valueObserver.start(),this.targetObserver.start(),this.outletObserver.start();try{this.controller.connect(),this.logDebugActivity("connect")}catch(e){this.handleError(e,"connecting controller")}}refresh(){this.outletObserver.refresh()}disconnect(){try{this.controller.disconnect(),this.logDebugActivity("disconnect")}catch(e){this.handleError(e,"disconnecting controller")}this.outletObserver.stop(),this.targetObserver.stop(),this.valueObserver.stop(),this.bindingObserver.stop()}get application(){return this.module.application}get identifier(){return this.module.identifier}get schema(){return this.application.schema}get dispatcher(){return this.application.dispatcher}get element(){return this.scope.element}get parentElement(){return this.element.parentElement}handleError(e,r,n={}){let{identifier:i,controller:s,element:a}=this;n=Object.assign({identifier:i,controller:s,element:a},n),this.application.handleError(e,`Error ${r}`,n)}targetConnected(e,r){this.invokeControllerMethod(`${r}TargetConnected`,e)}targetDisconnected(e,r){this.invokeControllerMethod(`${r}TargetDisconnected`,e)}outletConnected(e,r,n){this.invokeControllerMethod(`${ni(n)}OutletConnected`,e,r)}outletDisconnected(e,r,n){this.invokeControllerMethod(`${ni(n)}OutletDisconnected`,e,r)}invokeControllerMethod(e,...r){let n=this.controller;typeof n[e]=="function"&&n[e](...r)}};function Jc(t){return Zc(t,Xc(t))}function Zc(t,e){let r=ru(t),n=Qc(t.prototype,e);return Object.defineProperties(r.prototype,n),r}function Xc(t){return mr(t,"blessings").reduce((r,n)=>{let i=n(t);for(let s in i){let a=r[s]||{};r[s]=Object.assign(a,i[s])}return r},{})}function Qc(t,e){return tu(e).reduce((r,n)=>{let i=eu(t,e,n);return i&&Object.assign(r,{[n]:i}),r},{})}function eu(t,e,r){let n=Object.getOwnPropertyDescriptor(t,r);if(!(n&&"value"in n)){let s=Object.getOwnPropertyDescriptor(e,r).value;return n&&(s.get=n.get||s.get,s.set=n.set||s.set),s}}var tu=typeof Object.getOwnPropertySymbols=="function"?t=>[...Object.getOwnPropertyNames(t),...Object.getOwnPropertySymbols(t)]:Object.getOwnPropertyNames,ru=(()=>{function t(r){function n(){return Reflect.construct(r,arguments,new.target)}return n.prototype=Object.create(r.prototype,{constructor:{value:n}}),Reflect.setPrototypeOf(n,r),n}function e(){let n=t(function(){this.a.call(this)});return n.prototype.a=function(){},new n}try{return e(),t}catch{return n=>class extends n{}}})();function nu(t){return{identifier:t.identifier,controllerConstructor:Jc(t.controllerConstructor)}}var pi=class{constructor(e,r){this.application=e,this.definition=nu(r),this.contextsByScope=new WeakMap,this.connectedContexts=new Set}get identifier(){return this.definition.identifier}get controllerConstructor(){return this.definition.controllerConstructor}get contexts(){return Array.from(this.connectedContexts)}connectContextForScope(e){let r=this.fetchContextForScope(e);this.connectedContexts.add(r),r.connect()}disconnectContextForScope(e){let r=this.contextsByScope.get(e);r&&(this.connectedContexts.delete(r),r.disconnect())}fetchContextForScope(e){let r=this.contextsByScope.get(e);return r||(r=new di(this,e),this.contextsByScope.set(e,r)),r}},vi=class{constructor(e){this.scope=e}has(e){return this.data.has(this.getDataKey(e))}get(e){return this.getAll(e)[0]}getAll(e){let r=this.data.get(this.getDataKey(e))||"";return Bc(r)}getAttributeName(e){return this.data.getAttributeNameForKey(this.getDataKey(e))}getDataKey(e){return`${e}-class`}get data(){return this.scope.data}},gi=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get(e){let r=this.getAttributeNameForKey(e);return this.element.getAttribute(r)}set(e,r){let n=this.getAttributeNameForKey(e);return this.element.setAttribute(n,r),this.get(e)}has(e){let r=this.getAttributeNameForKey(e);return this.element.hasAttribute(r)}delete(e){if(this.has(e)){let r=this.getAttributeNameForKey(e);return this.element.removeAttribute(r),!0}else return!1}getAttributeNameForKey(e){return`data-${this.identifier}-${Eo(e)}`}},mi=class{constructor(e){this.warnedKeysByObject=new WeakMap,this.logger=e}warn(e,r,n){let i=this.warnedKeysByObject.get(e);i||(i=new Set,this.warnedKeysByObject.set(e,i)),i.has(r)||(i.add(r),this.logger.warn(n,e))}};function bi(t,e){return`[${t}~="${e}"]`}var yi=class{constructor(e){this.scope=e}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((r,n)=>r||this.findTarget(n)||this.findLegacyTarget(n),void 0)}findAll(...e){return e.reduce((r,n)=>[...r,...this.findAllTargets(n),...this.findAllLegacyTargets(n)],[])}findTarget(e){let r=this.getSelectorForTargetName(e);return this.scope.findElement(r)}findAllTargets(e){let r=this.getSelectorForTargetName(e);return this.scope.findAllElements(r)}getSelectorForTargetName(e){let r=this.schema.targetAttributeForScope(this.identifier);return bi(r,e)}findLegacyTarget(e){let r=this.getLegacySelectorForTargetName(e);return this.deprecate(this.scope.findElement(r),e)}findAllLegacyTargets(e){let r=this.getLegacySelectorForTargetName(e);return this.scope.findAllElements(r).map(n=>this.deprecate(n,e))}getLegacySelectorForTargetName(e){let r=`${this.identifier}.${e}`;return bi(this.schema.targetAttribute,r)}deprecate(e,r){if(e){let{identifier:n}=this,i=this.schema.targetAttribute,s=this.schema.targetAttributeForScope(n);this.guide.warn(e,`target:${r}`,`Please replace ${i}="${n}.${r}" with ${s}="${r}". The ${i} attribute is deprecated and will be removed in a future version of Stimulus.`)}return e}get guide(){return this.scope.guide}},wi=class{constructor(e,r){this.scope=e,this.controllerElement=r}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get schema(){return this.scope.schema}has(e){return this.find(e)!=null}find(...e){return e.reduce((r,n)=>r||this.findOutlet(n),void 0)}findAll(...e){return e.reduce((r,n)=>[...r,...this.findAllOutlets(n)],[])}getSelectorForOutletName(e){let r=this.schema.outletAttributeForScope(this.identifier,e);return this.controllerElement.getAttribute(r)}findOutlet(e){let r=this.getSelectorForOutletName(e);if(r)return this.findElement(r,e)}findAllOutlets(e){let r=this.getSelectorForOutletName(e);return r?this.findAllElements(r,e):[]}findElement(e,r){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,r))[0]}findAllElements(e,r){return this.scope.queryElements(e).filter(i=>this.matchesElement(i,e,r))}matchesElement(e,r,n){let i=e.getAttribute(this.scope.schema.controllerAttribute)||"";return e.matches(r)&&i.split(" ").includes(n)}},_i=class t{constructor(e,r,n,i){this.targets=new yi(this),this.classes=new vi(this),this.data=new gi(this),this.containsElement=s=>s.closest(this.controllerSelector)===this.element,this.schema=e,this.element=r,this.identifier=n,this.guide=new mi(i),this.outlets=new wi(this.documentScope,r)}findElement(e){return this.element.matches(e)?this.element:this.queryElements(e).find(this.containsElement)}findAllElements(e){return[...this.element.matches(e)?[this.element]:[],...this.queryElements(e).filter(this.containsElement)]}queryElements(e){return Array.from(this.element.querySelectorAll(e))}get controllerSelector(){return bi(this.schema.controllerAttribute,this.identifier)}get isDocumentScope(){return this.element===document.documentElement}get documentScope(){return this.isDocumentScope?this:new t(this.schema,document.documentElement,this.identifier,this.guide.logger)}},Ei=class{constructor(e,r,n){this.element=e,this.schema=r,this.delegate=n,this.valueListObserver=new Wr(this.element,this.controllerAttribute,this),this.scopesByIdentifierByElement=new WeakMap,this.scopeReferenceCounts=new WeakMap}start(){this.valueListObserver.start()}stop(){this.valueListObserver.stop()}get controllerAttribute(){return this.schema.controllerAttribute}parseValueForToken(e){let{element:r,content:n}=e;return this.parseValueForElementAndIdentifier(r,n)}parseValueForElementAndIdentifier(e,r){let n=this.fetchScopesByIdentifierForElement(e),i=n.get(r);return i||(i=this.delegate.createScopeForElementAndIdentifier(e,r),n.set(r,i)),i}elementMatchedValue(e,r){let n=(this.scopeReferenceCounts.get(r)||0)+1;this.scopeReferenceCounts.set(r,n),n==1&&this.delegate.scopeConnected(r)}elementUnmatchedValue(e,r){let n=this.scopeReferenceCounts.get(r);n&&(this.scopeReferenceCounts.set(r,n-1),n==1&&this.delegate.scopeDisconnected(r))}fetchScopesByIdentifierForElement(e){let r=this.scopesByIdentifierByElement.get(e);return r||(r=new Map,this.scopesByIdentifierByElement.set(e,r)),r}},ki=class{constructor(e){this.application=e,this.scopeObserver=new Ei(this.element,this.schema,this),this.scopesByIdentifier=new ct,this.modulesByIdentifier=new Map}get element(){return this.application.element}get schema(){return this.application.schema}get logger(){return this.application.logger}get controllerAttribute(){return this.schema.controllerAttribute}get modules(){return Array.from(this.modulesByIdentifier.values())}get contexts(){return this.modules.reduce((e,r)=>e.concat(r.contexts),[])}start(){this.scopeObserver.start()}stop(){this.scopeObserver.stop()}loadDefinition(e){this.unloadIdentifier(e.identifier);let r=new pi(this.application,e);this.connectModule(r);let n=e.controllerConstructor.afterLoad;n&&n.call(e.controllerConstructor,e.identifier,this.application)}unloadIdentifier(e){let r=this.modulesByIdentifier.get(e);r&&this.disconnectModule(r)}getContextForElementAndIdentifier(e,r){let n=this.modulesByIdentifier.get(r);if(n)return n.contexts.find(i=>i.element==e)}proposeToConnectScopeForElementAndIdentifier(e,r){let n=this.scopeObserver.parseValueForElementAndIdentifier(e,r);n?this.scopeObserver.elementMatchedValue(n.element,n):console.error(`Couldn't find or create scope for identifier: "${r}" and element:`,e)}handleError(e,r,n){this.application.handleError(e,r,n)}createScopeForElementAndIdentifier(e,r){return new _i(this.schema,e,r,this.logger)}scopeConnected(e){this.scopesByIdentifier.add(e.identifier,e);let r=this.modulesByIdentifier.get(e.identifier);r&&r.connectContextForScope(e)}scopeDisconnected(e){this.scopesByIdentifier.delete(e.identifier,e);let r=this.modulesByIdentifier.get(e.identifier);r&&r.disconnectContextForScope(e)}connectModule(e){this.modulesByIdentifier.set(e.identifier,e),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(n=>e.connectContextForScope(n))}disconnectModule(e){this.modulesByIdentifier.delete(e.identifier),this.scopesByIdentifier.getValuesForKey(e.identifier).forEach(n=>e.disconnectContextForScope(n))}},iu={controllerAttribute:"data-controller",actionAttribute:"data-action",targetAttribute:"data-target",targetAttributeForScope:t=>`data-${t}-target`,outletAttributeForScope:(t,e)=>`data-${t}-${e}-outlet`,keyMappings:Object.assign(Object.assign({enter:"Enter",tab:"Tab",esc:"Escape",space:" ",up:"ArrowUp",down:"ArrowDown",left:"ArrowLeft",right:"ArrowRight",home:"Home",end:"End",page_up:"PageUp",page_down:"PageDown"},go("abcdefghijklmnopqrstuvwxyz".split("").map(t=>[t,t]))),go("0123456789".split("").map(t=>[t,t])))};function go(t){return t.reduce((e,[r,n])=>Object.assign(Object.assign({},e),{[r]:n}),{})}var Gr=class{constructor(e=document.documentElement,r=iu){this.logger=console,this.debug=!1,this.logDebugActivity=(n,i,s={})=>{this.debug&&this.logFormattedMessage(n,i,s)},this.element=e,this.schema=r,this.dispatcher=new ri(this),this.router=new ki(this),this.actionDescriptorFilters=Object.assign({},Fc)}static start(e,r){let n=new this(e,r);return n.start(),n}async start(){await su(),this.logDebugActivity("application","starting"),this.dispatcher.start(),this.router.start(),this.logDebugActivity("application","start")}stop(){this.logDebugActivity("application","stopping"),this.dispatcher.stop(),this.router.stop(),this.logDebugActivity("application","stop")}register(e,r){this.load({identifier:e,controllerConstructor:r})}registerActionOption(e,r){this.actionDescriptorFilters[e]=r}load(e,...r){(Array.isArray(e)?e:[e,...r]).forEach(i=>{i.controllerConstructor.shouldLoad&&this.router.loadDefinition(i)})}unload(e,...r){(Array.isArray(e)?e:[e,...r]).forEach(i=>this.router.unloadIdentifier(i))}get controllers(){return this.router.contexts.map(e=>e.controller)}getControllerForElementAndIdentifier(e,r){let n=this.router.getContextForElementAndIdentifier(e,r);return n?n.controller:null}handleError(e,r,n){var i;this.logger.error(`%s

%o

%o`,r,e,n),(i=window.onerror)===null||i===void 0||i.call(window,r,"",0,0,e)}logFormattedMessage(e,r,n={}){n=Object.assign({application:this},n),this.logger.groupCollapsed(`${e} #${r}`),this.logger.log("details:",Object.assign({},n)),this.logger.groupEnd()}};function su(){return new Promise(t=>{document.readyState=="loading"?document.addEventListener("DOMContentLoaded",()=>t()):t()})}function ou(t){return mr(t,"classes").reduce((r,n)=>Object.assign(r,au(n)),{})}function au(t){return{[`${t}Class`]:{get(){let{classes:e}=this;if(e.has(t))return e.get(t);{let r=e.getAttributeName(t);throw new Error(`Missing attribute "${r}"`)}}},[`${t}Classes`]:{get(){return this.classes.getAll(t)}},[`has${gr(t)}Class`]:{get(){return this.classes.has(t)}}}}function lu(t){return mr(t,"outlets").reduce((r,n)=>Object.assign(r,cu(n)),{})}function mo(t,e,r){return t.application.getControllerForElementAndIdentifier(e,r)}function bo(t,e,r){let n=mo(t,e,r);if(n||(t.application.router.proposeToConnectScopeForElementAndIdentifier(e,r),n=mo(t,e,r),n))return n}function cu(t){let e=ni(t);return{[`${e}Outlet`]:{get(){let r=this.outlets.find(t),n=this.outlets.getSelectorForOutletName(t);if(r){let i=bo(this,r,t);if(i)return i;throw new Error(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`)}throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${n}".`)}},[`${e}Outlets`]:{get(){let r=this.outlets.findAll(t);return r.length>0?r.map(n=>{let i=bo(this,n,t);if(i)return i;console.warn(`The provided outlet element is missing an outlet controller "${t}" instance for host controller "${this.identifier}"`,n)}).filter(n=>n):[]}},[`${e}OutletElement`]:{get(){let r=this.outlets.find(t),n=this.outlets.getSelectorForOutletName(t);if(r)return r;throw new Error(`Missing outlet element "${t}" for host controller "${this.identifier}". Stimulus couldn't find a matching outlet element using selector "${n}".`)}},[`${e}OutletElements`]:{get(){return this.outlets.findAll(t)}},[`has${gr(e)}Outlet`]:{get(){return this.outlets.has(t)}}}}function uu(t){return mr(t,"targets").reduce((r,n)=>Object.assign(r,hu(n)),{})}function hu(t){return{[`${t}Target`]:{get(){let e=this.targets.find(t);if(e)return e;throw new Error(`Missing target element "${t}" for "${this.identifier}" controller`)}},[`${t}Targets`]:{get(){return this.targets.findAll(t)}},[`has${gr(t)}Target`]:{get(){return this.targets.has(t)}}}}function fu(t){let e=Yc(t,"values"),r={valueDescriptorMap:{get(){return e.reduce((n,i)=>{let s=xo(i,this.identifier),a=this.data.getAttributeNameForKey(s.key);return Object.assign(n,{[a]:s})},{})}}};return e.reduce((n,i)=>Object.assign(n,du(i)),r)}function du(t,e){let r=xo(t,e),{key:n,name:i,reader:s,writer:a}=r;return{[i]:{get(){let l=this.data.get(n);return l!==null?s(l):r.defaultValue},set(l){l===void 0?this.data.delete(n):this.data.set(n,a(l))}},[`has${gr(i)}`]:{get(){return this.data.has(n)||r.hasCustomDefaultValue}}}}function xo([t,e],r){return mu({controller:r,token:t,typeDefinition:e})}function Jr(t){switch(t){case Array:return"array";case Boolean:return"boolean";case Number:return"number";case Object:return"object";case String:return"string"}}function vr(t){switch(typeof t){case"boolean":return"boolean";case"number":return"number";case"string":return"string"}if(Array.isArray(t))return"array";if(Object.prototype.toString.call(t)==="[object Object]")return"object"}function pu(t){let{controller:e,token:r,typeObject:n}=t,i=fo(n.type),s=fo(n.default),a=i&&s,l=i&&!s,c=!i&&s,u=Jr(n.type),f=vr(t.typeObject.default);if(l)return u;if(c)return f;if(u!==f){let p=e?`${e}.${r}`:r;throw new Error(`The specified default value for the Stimulus Value "${p}" must match the defined type "${u}". The provided default value of "${n.default}" is of type "${f}".`)}if(a)return u}function vu(t){let{controller:e,token:r,typeDefinition:n}=t,s=pu({controller:e,token:r,typeObject:n}),a=vr(n),l=Jr(n),c=s||a||l;if(c)return c;let u=e?`${e}.${n}`:r;throw new Error(`Unknown value type "${u}" for "${r}" value`)}function gu(t){let e=Jr(t);if(e)return yo[e];let r=ii(t,"default"),n=ii(t,"type"),i=t;if(r)return i.default;if(n){let{type:s}=i,a=Jr(s);if(a)return yo[a]}return t}function mu(t){let{token:e,typeDefinition:r}=t,n=`${Eo(e)}-value`,i=vu(t);return{type:i,key:n,name:Ai(n),get defaultValue(){return gu(r)},get hasCustomDefaultValue(){return vr(r)!==void 0},reader:bu[i],writer:wo[i]||wo.default}}var yo={get array(){return[]},boolean:!1,number:0,get object(){return{}},string:""},bu={array(t){let e=JSON.parse(t);if(!Array.isArray(e))throw new TypeError(`expected value of type "array" but instead got value "${t}" of type "${vr(e)}"`);return e},boolean(t){return!(t=="0"||String(t).toLowerCase()=="false")},number(t){return Number(t.replace(/_/g,""))},object(t){let e=JSON.parse(t);if(e===null||typeof e!="object"||Array.isArray(e))throw new TypeError(`expected value of type "object" but instead got value "${t}" of type "${vr(e)}"`);return e},string(t){return t}},wo={default:yu,array:_o,object:_o};function _o(t){return JSON.stringify(t)}function yu(t){return`${t}`}var tt=class{constructor(e){this.context=e}static get shouldLoad(){return!0}static afterLoad(e,r){}get application(){return this.context.application}get scope(){return this.context.scope}get element(){return this.scope.element}get identifier(){return this.scope.identifier}get targets(){return this.scope.targets}get outlets(){return this.scope.outlets}get classes(){return this.scope.classes}get data(){return this.scope.data}initialize(){}connect(){}disconnect(){}dispatch(e,{target:r=this.element,detail:n={},prefix:i=this.identifier,bubbles:s=!0,cancelable:a=!0}={}){let l=i?`${i}:${e}`:e,c=new CustomEvent(l,{detail:n,bubbles:s,cancelable:a});return r.dispatchEvent(c),c}};tt.blessings=[ou,uu,fu,lu];tt.targets=[];tt.outlets=[];tt.values={};var xi=Gr.start();xi.register("navigation",class extends tt{static get targets(){return["button"]}connect(){this.element.addEventListener("keydown",this.trapEscape.bind(this))}trapEscape(t){t.key==="Escape"&&this.close()}switch(){this.buttonTarget.getAttribute("aria-expanded")==="true"?this.buttonTarget.setAttribute("aria-expanded","false"):this.buttonTarget.setAttribute("aria-expanded","true")}close(){this.buttonTarget.setAttribute("aria-expanded","false"),this.buttonTarget.focus()}});xi.register("profile",class extends tt{static get targets(){return["sectionNatural","sectionLegal","firstName","lastName","legalName","controlAddress"]}initialize(){this.switchAddressForNode(this.controlAddressTarget);let t=document.querySelector('input[name="entity_type"][checked]');this.switchEntityTypeForNode(t)}switchAddress(t){this.switchAddressForNode(t.target)}switchAddressForNode(t){let e=document.querySelector("#address");e.hidden=!t.checked}switchEntityType(t){this.switchEntityTypeForNode(t.target)}switchEntityTypeForNode(t){t.value==="natural"?(this.sectionNaturalTarget.hidden=!1,this.sectionLegalTarget.hidden=!0,this.firstNameTarget.required=!0,this.lastNameTarget.required=!0,this.legalNameTarget.required=!1,this.controlAddressTarget.checked=this.controlAddressTarget.defaultChecked,this.controlAddressTarget.hidden=!1):(this.sectionNaturalTarget.hidden=!0,this.sectionLegalTarget.hidden=!1,this.firstNameTarget.required=!1,this.lastNameTarget.required=!1,this.legalNameTarget.required=!0,this.controlAddressTarget.checked=!0,this.controlAddressTarget.hidden=!0),this.switchAddressForNode(this.controlAddressTarget)}});xi.register("amount-selector",class extends tt{static get targets(){return["amount","radio","totalAmount"]}initialize(){let t=this.data.get("initialAmount");this.setAmount(t)}setAmount(t){let e=this.amountTarget;e.value=t,this.refreshTotalAmount()}select(t){let e=t.target;this.setAmount(e.dataset.value)}change(t){this.radioTargets.forEach(function(e){e.checked=!1}),this.refreshTotalAmount()}refreshTotalAmount(){if(this.totalAmountTarget){let t=parseInt(this.amountTarget.value,10),e=parseInt(this.data.get("countAccounts"),10);this.totalAmountTarget.innerHTML=t*e}}});})();
//# sourceMappingURL=application.js.map
