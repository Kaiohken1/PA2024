import{_}from"./base-button.vue_vue_type_script_setup_true_lang-3d6cef8e.js";import{_ as y,a as v}from"./input-label.vue_vue_type_script_setup_true_lang-b020cb97.js";import{_ as g}from"./input-text.vue_vue_type_script_setup_true_lang-c368a274.js";import{d as x,T as V,o as c,h as u,f as o,b as t,u as a,w as p,a as h,e as B,H as I,g as N}from"./app-8d2ddf0a.js";import{u as S}from"./use-auth-9835d026.js";const b=o("header",null,[o("h2",{class:"text-lg font-medium text-gray-900"},"Profile Information"),o("p",{class:"mt-1 text-sm text-gray-600"},"Update your account's profile information and email address.")],-1),E={class:"space-y-1"},T={class:"space-y-1"},$={class:"flex items-center gap-4"},k={key:0,class:"text-sm text-gray-600"},H=x({__name:"user-update-profile-information-form",props:{mustVerifyEmail:{},status:{}},setup(w){const n=S().value,e=V({name:n.name,email:n.email}),d=()=>{e.patch(route("ltu.profile.update"),{preserveScroll:!0})};return(C,s)=>{const m=y,i=g,l=v,f=_;return c(),u("section",null,[b,o("form",{class:"mt-6 space-y-6",onSubmit:N(d,["prevent"])},[o("div",E,[t(m,{for:"name",value:"Name"}),t(i,{id:"name",modelValue:a(e).name,"onUpdate:modelValue":s[0]||(s[0]=r=>a(e).name=r),error:a(e).errors.name,type:"text",required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue","error"]),t(l,{message:a(e).errors.name},null,8,["message"])]),o("div",T,[t(m,{for:"email",value:"Email"}),t(i,{id:"email",modelValue:a(e).email,"onUpdate:modelValue":s[1]||(s[1]=r=>a(e).email=r),error:a(e).errors.email,type:"email",autocomplete:"username"},null,8,["modelValue","error"]),t(l,{message:a(e).errors.email},null,8,["message"])]),o("div",$,[t(f,{type:"submit",size:"md",variant:"primary","is-loading":a(e).processing},{default:p(()=>[h(" Save ")]),_:1},8,["is-loading"]),t(I,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:p(()=>[a(e).recentlySuccessful?(c(),u("p",k,"Saved.")):B("",!0)]),_:1})])],32)])}}});export{H as _};
