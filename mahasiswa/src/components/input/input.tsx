import React, { ChangeEvent, Component, RefObject, createRef } from "react";
import { ModelInput } from "./model";
import { clsx } from "clsx";
import Icon from "../icon/icon";

const sizeLabel = {
  small: "text-xsm",
  medium: "text-sm",
  large: "text-lg",
};

const sizeInput = {
  small: "text-xsm px-2.5 placeholder:text-xsm",
  medium: "text-sm px-2.5 placeholder:text-sm",
  large: "text-lg px-3 placeholder:text-lg",
};

const sizeFont = {
  small: "text-xsm placeholder:text-xsm",
  medium: "text-sm placeholder:text-sm",
  large: "text-lg placeholder:text-lg",
};

class Input extends Component<ModelInput> {
  state: Readonly<{
    visiblePassword: boolean;
    files: any;
    filename: any;
    refInputFile: RefObject<any>;
  }>;
  constructor(props: ModelInput) {
    super(props);

    this.state = {
      visiblePassword: false,
      files: undefined,
      filename: this.props.filename,
      refInputFile: createRef(),
    };
    this.onChange = this.onChange.bind(this);
    this.changeFile = this.changeFile.bind(this);
    this.deleteFile = this.deleteFile.bind(this);
  }

  changeVisiblePassword(visible: boolean) {
    return visible ? "text" : "password";
  }

  changeFile(event: any) {
    if (event.target.files && event.target.files.length > 0) {
      this.props.onValueChange(event.target.files[0]);
      this.setState({
        files: event.target.files[0],
      });
    }
  }

  deleteFile() {
    this.props.onValueChange(null);
    this.setState({
      files: undefined,
    });
  }

  previewFile(event: any) {
    const link = window.URL.createObjectURL(event);
    window.open(link, "_blank");
  }

  onChange = (e: ChangeEvent<HTMLInputElement>) => {
    const val = Number(e.target.value);
    if (Number.isInteger(val) && val >= 0) {
      return val;
    }
  };
  render(): React.ReactNode {
    return (
      <div className={this.props.className}>
        <p
          className={clsx(
            "mb-1.5 font-intersemibold tracking-tight inline-block",
            sizeLabel[this.props.size ?? "medium"]
          )}
        >
          {this.props.isRequired && (
            <span className=" text-red-500 font-intersemibold">*</span>
          )}{" "}
          {this.props.label}
        </p>
        {(() => {
          switch (this.props.type) {
            case "text":
            case "password":
            case "number":
              return (
                <div className="relative">
                  <input
                    required={this.props.isRequired}
                    readOnly={this.props.readonly}
                    type={
                      this.props.type === "password"
                        ? this.changeVisiblePassword(this.state.visiblePassword)
                        : this.props.type
                    }
                    className={clsx(
                      this.props.readonly
                        ? "bg-gray-100 cursor-not-allowed placeholder:text-gray-500"
                        : "",
                      "placeholder:font-interregular placeholder:text-slate-400 font-interregular",
                      "border border-gray-400/70 p-2 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 block w-full",
                      sizeInput[this.props.size ?? "medium"],
                      this.props.type === "password" ? "pr-10" : ""
                    )}
                    defaultValue={this.props.defaultValue}
                    placeholder={this.props.placeholder ?? "Tulis disini"}
                    onChange={
                      this.props.type === "number"
                        ? this.onChange
                        : this.props.onChange
                    }
                    onInput={(event) =>
                      this.props.onValueChange
                        ? this.props.onValueChange(event.currentTarget.value)
                        : null
                    }
                  />
                  {this.props.type === "password" && (
                    <div className="absolute inset-y-0 right-0 flex items-center pe-3 top-0 bottom-0 cursor-pointer">
                      {this.state.visiblePassword && (
                        <Icon
                          icon="eye"
                          width={15}
                          height={15}
                          onClick={() =>
                            this.setState({ visiblePassword: false })
                          }
                        />
                      )}
                      {!this.state.visiblePassword && (
                        <Icon
                          icon="hideEye"
                          width={15}
                          height={15}
                          onClick={() =>
                            this.setState({ visiblePassword: true })
                          }
                        />
                      )}
                    </div>
                  )}
                </div>
              );
            case "textarea":
              return (
                <textarea
                  required={this.props.isRequired}
                  readOnly={this.props.readonly}
                  rows={this.props.autosize ? 1 : 2}
                  className={clsx(
                    "placeholder:font-interregular placeholder:text-slate-400 font-interregular",
                    "border border-gray-400/70 p-2 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 block w-full",
                    sizeFont[this.props.size ?? "medium"]
                  )}
                  defaultValue={this.props.defaultValue}
                  placeholder={this.props.placeholder ?? "Tulis disini"}
                  onInput={(event) =>
                    this.props.onValueChange
                      ? this.props.onValueChange(event.currentTarget.value)
                      : null
                  }
                />
              );
            case "date":
            case "datetime-local":
            case "time":
            case "month":
              return (
                <input
                  required={this.props.isRequired}
                  readOnly={this.props.readonly}
                  type={this.props.type}
                  className={clsx(
                    this.props.readonly ? "bg-gray-200 cursor-not-allowed" : "",
                    "placeholder:font-interregular placeholder:text-slate-400 font-interregular",
                    "border border-gray-400/70 p-2 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 block w-full",
                    sizeInput[this.props.size ?? "medium"]
                  )}
                  defaultValue={this.props.defaultValue}
                  placeholder={this.props.placeholder ?? "Tulis disini"}
                  onChange={this.props.onChange}
                  onInput={(event) =>
                    this.props.onValueChange
                      ? this.props.onValueChange(event.currentTarget.value)
                      : null
                  }
                />
              );
            case "upload":
              return (
                <div>
                  {!this.state.filename && (
                    <input
                      type="file"
                      accept={this.props.accept}
                      ref={this.state.refInputFile}
                      className={clsx("hidden")}
                      onChange={
                        this.props.onValueChange
                          ? this.changeFile
                          : this.props.onChange
                      }
                    />
                  )}
                  {this.state.filename && (
                    <div className="block relative">
                      <div className=" flex gap-x-2 my-auto">
                        <button
                          onClick={() => this.setState({ filename: undefined })}
                          className="bg-rose-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
                        >
                          Hapus {this.props.label}
                        </button>
                        <button
                          onClick={() =>
                            this.props.preview(
                              this.state.filename,
                              this.props.preview_action
                            )
                          }
                          className="bg-emerald-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
                        >
                          Lihat {this.props.label}
                        </button>
                      </div>
                      <p className="text-xs mt-1 font-interregular">
                        {this.state.filename as any}
                      </p>
                    </div>
                  )}
                  {!this.state.filename && !this.state.files && (
                    <button
                      onClick={() => this.state.refInputFile.current?.click()}
                      className="bg-blue-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
                    >
                      Pilih {this.props.label}
                    </button>
                  )}
                  {this.state.files && (
                    <div className="block relative">
                      <div className=" flex gap-x-2 my-auto">
                        <button
                          onClick={() => this.deleteFile()}
                          className="bg-rose-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
                        >
                          Hapus {this.props.label}
                        </button>
                        <button
                          onClick={() => this.previewFile(this.state.files)}
                          className="bg-emerald-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
                        >
                          Lihat {this.props.label}
                        </button>
                      </div>
                      <p className="text-xs mt-1 font-interregular">
                        {this.state.files?.name as any} -{" "}
                        <span className=" text-gray-500">
                          ( {this.state.files?.size / 1000} Kb )
                        </span>
                      </p>
                    </div>
                  )}
                </div>
              );
          }
        })()}
        <i className="text-gray-500 font-interregular text-xs">
          {this.props.description}
        </i>
      </div>
    );
  }
}

export default Input;
